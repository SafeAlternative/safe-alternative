<?php

include_once 'team-calculator.class.php';

// Check if WooCommerce is active
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    function Team_Shipping_Method()
    {
        if (!class_exists('Team_Shipping_Method')) {
            class Team_Shipping_Method extends WC_Shipping_Method
            {
                // Constructor for your shipping class
                public function __construct()
                {
                    $this->id  = 'team';
                    $this->method_title = __('Team Shipping', 'team');
                    $this->method_description = __('Team Shipping Method for courier', 'team');

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array('RO');

                    $this->init();

                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Team Shipping', 'team');
                }

                // init
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                // define settings field for this shipping
                function init_form_fields()
                {
                    $this->form_fields = array(
                        'title' => array(
                            'title' => __('Denumire metoda livrare *', 'team'),
                            'type' => 'text',
                            'description' => __('Denumirea metodei de livrare in Cart si Checkout, vizibila de catre client.', 'team'),
                            'default' => __('Team', 'team'),
                            'desc_tip' => true,
                            'custom_attributes' => array('required' => 'required')
                        ),
                        'tarif_contract' => array(
                            'title' => __('Afisare tarif contract', 'team'),
                            'type' => 'select',
                            'default' => 'no',
                            'css' => 'width:400px;',
                            'description' => __('Pentru a activa aceasta optiune trebuie sa aveti si metoda Team - AWB activata si configurata.', 'team'),
                            'desc_tip' => true,
                            'options' => array(
                                'no' => __('Nu', 'team'),
                                'yes' => __('Da', 'team')
                            ),
                        ),
                        'prag_gratis_Bucuresti' => array(
                            'title' => __('Prag gratis Bucuresti', 'team'),
                            'type' => 'text',
                            'default' => __('250', 'team')
                        ),
                        'suma_fixa_Bucuresti' => array(
                            'title' => __('Suma fixa Bucuresti', 'team'),
                            'type' => 'text',
                            'default' => __('15', 'team')
                        ),
                        'prag_gratis_provincie' => array(
                            'title' => __('Prag gratis provincie', 'team'),
                            'type' => 'text',
                            'default' => __('250', 'team')
                        ),
                        'suma_fixa_provincie' => array(
                            'title' => __('Suma fixa provincie', 'team'),
                            'type' => 'text',
                            'default' => __('18', 'team')
                        ),
                        'tarif_implicit' => array(
                            'title' => __('Tarif implicit', 'team'),
                            'type' => 'number',
                            'default' => __('0', 'team'),
                            'desc_tip' => true,
                            'custom_attributes' => array('step' => '0.01', 'min' => '0'),
                            'description' => __('Tariful implicit pentru metoda de livrare, pentru a arata o suma atunci cand nu este introdusa adresa de livrare.', 'team')
                        ),
                        'tarif_maxim' => array(
                            'title' => __('Tarif maxim livrare', 'team'),
                            'type' => 'text',
                            'default' => __('40', 'team'),
                            'desc_tip' => true,
                            'description' => __('Tariful final nu poate depasi aceasta valoare.', 'team')
                        ),
                    );
                }

                public function admin_options()
                {
                    echo '<h1>SafeAlternative - Metoda de livrare Team</h1><br/><table class="form-table">';
                    $this->generate_settings_html();
                    echo '</table>';
                }

                private function fix_format($value)
                {
                    $value = str_replace(',', '.', $value);
                    return $value;
                }

                ////////////////////////////////////////////////////////////////
                //  Calculate //////////////////////////////////////////////////
                ////////////////////////////////////////////////////////////////

                public function calculate_shipping($package = array())
                {
                    $prag_gratis_Bucuresti = $this->get_option('prag_gratis_Bucuresti');
                    $suma_fixa_Bucuresti = $this->get_option('suma_fixa_Bucuresti');
                    $prag_gratis_provincie = $this->get_option('prag_gratis_provincie');
                    $suma_fixa_provincie = $this->get_option('suma_fixa_provincie');

                    $orasdest = strtolower($package['destination']['city']);
                    $judetdest_abvr = $package['destination']['state'] ?? '';
                    $judetdest = safealternative_get_counties_list()[$judetdest_abvr];
                    $adresadest = $package['destination']['address'];
                    $postcodedest = $package['destination']['postcode'];
                    $tarif_contract = $this->get_option('tarif_contract');
                    $tarif_maxim = $this->get_option('tarif_maxim');
                    if (empty($tarif_maxim)) $tarif_maxim = 99999;

                    $ramburs = 0;
                    $greutate = 0;
                    $valoare_cos = 0;

                    foreach ($package['contents'] as $product) {
                        $ramburs += ($product['line_total'] + $product['line_tax']);
                        $valoare_cos += ($product['line_total'] + $product['line_tax']);

                        // WooCommerce 3.0 or later.
                        if (method_exists($product['data'], 'get_height')) {
                            $greutate += $this->fix_format($product['data']->get_weight() ?: 1) * $product['quantity'];
                        } else {
                            $greutate += $this->fix_format($product['data']->weight ?: 1) * $product['quantity'];
                        }
                    }

                    $weight_type = get_option('woocommerce_weight_unit');
                    if ($weight_type == 'g') {
                        $greutate = $greutate / 1000;
                    }

                    if ($greutate < 1) {
                        $greutate = 1;
                    } else {
                        $greutate = round($greutate);
                    }

                    /////////////////////////////////////////////////////////
                    $label = $this->title;

                    if ($prag_gratis_Bucuresti == $prag_gratis_provincie && $ramburs >= $prag_gratis_Bucuresti) {
                        $transport = 0;
                        $label = $this->title . ': Gratis';
                    } else {
                        if ($tarif_contract == 'yes' && class_exists('TeamAWB')) {
                            global $wpdb;
                            $first_zip = $wpdb->get_var("SELECT ZipCode FROM courier_zipcodes WHERE County='$judetdest' AND  City LIKE '%$orasdest%'");
                            $zip = $postcodedest ?? $first_zip;

                            if (isset($_REQUEST['payment_method']) && (addslashes($_REQUEST['payment_method']) != 'cod')) {
                                $ramburs = 0;
                            }

                            $req_vars = [
                                'type' => get_option('team_package_type'),
                                'service_type' => get_option('team_service'),
                                'cnt' => get_option('team_parcel_count'),
                                'retur' => get_option('team_return'),
                                'retur_type' => get_option('team_return_type'),
                                'ramburs' => $ramburs,
                                'ramburs_type' => 'cash',
                                'service_41' => get_option('team_open_package'),
                                'service_42' => get_option('team_sat_delivery'),
                                'service_51' => get_option('team_tax_urgent_express'),
                                'service_62' => get_option('team_change_delivery_address'),
                                'service_63' => get_option('team_special_delivery_hour'),
                                'service_64' => get_option('team_swap_package'),
                                'service_66' => get_option('team_retur_delivery_confirmation'),
                                'service_67' => get_option('team_retur_documents'),
                                'service_73' => get_option('team_3rd_national_delivery'),
                                'service_84' => get_option('team_retur_expedition_undelivered_package'),
                                'service_104' => get_option('team_awb_by_delivery_agent'),
                                'service_108' => get_option('team_labeling_package_with_awb'),
                                'service_292' => get_option('team_multiple_packages'),
                                'insurance' => get_option('team_insurance'),
                                'weight' =>  $greutate,
                                'content' => get_option('team_content'),
                                'fragile' => get_option('team_is_fragile'),
                                'payer' => get_option('team_payer'),
                                'from_county' => get_option('team_county'),
                                'from_city' => get_option('team_city'),
                                'from_address' => get_option('team_address'),
                                'from_zipcode' => get_option('team_postcode'),
                                'to_county' => $judetdest,
                                'to_city' => $orasdest,
                                'to_address' => $adresadest,
                                'to_zipcode' => $zip,
                            ];

                            $transport = (float) $this->get_option('tarif_implicit') ?: 0;

                            if (empty($judetdest_abvr) || empty($orasdest)) return;

                            try {
                                $bypass = false;
                                if ($judetdest_abvr == "B") {
                                    if ($valoare_cos >= $prag_gratis_Bucuresti) {
                                        $transport = 0;
                                        $bypass = true;
                                    }
                                } else {
                                    if ($valoare_cos >= $prag_gratis_provincie) {
                                        $transport = $bypass = 0;
                                        $bypass = true;
                                    }
                                }

                                if (!$bypass) {
                                    $transport = (new SafealternativeTeamShippingClass)->calculate($req_vars);
                                }

                                if ($transport == 0) $label = $this->title . ': Gratis';
                            } catch (\Exception $e) {
                            }
                        } else {
                            if ($judetdest_abvr && $orasdest) {

                                if ($judetdest_abvr == "B") {
                                    if ($valoare_cos < $prag_gratis_Bucuresti) $transport = $suma_fixa_Bucuresti;
                                    if ($valoare_cos >= $prag_gratis_Bucuresti) $transport = 0;
                                } else {
                                    if ($valoare_cos < $prag_gratis_provincie) $transport = $suma_fixa_provincie;
                                    if ($valoare_cos >= $prag_gratis_provincie) $transport = 0;
                                }

                                if ($transport == 0) $label = $this->title . ': Gratis';
                            } else {
                                $transport = (float) $this->get_option('tarif_implicit') ?: 0;
                            }
                        }
                    }

                    $transport = min($transport, $tarif_maxim);

                    $args = array(
                        'id' => $this->id,
                        'label' => $label,
                        'cost' => $transport,
                        'taxes' => true
                    );

                    if ($transport !== 0 || (strpos($label, 'Gratis') !== false)) {
                        $args = apply_filters('safealternative_overwrite_team_shipping', $args, $judetdest, $orasdest);
                        $this->add_rate($args);
                    }
                } // end method
            } // end class
        } // end ifclass exist
    } // end function

    add_action('woocommerce_shipping_init', 'team_shipping_method');

    add_filter('woocommerce_shipping_methods', 'add_team_shipping_method');
    function add_team_shipping_method($methods)
    {
        $methods[] = 'Team_Shipping_Method';
        return $methods;
    }

    // activate city
    add_filter('woocommerce_shipping_calculator_enable_city', '__return_true');
    add_filter('woocommerce_shipping_calculator_enable_postcode', '__return_true');

    ////INCLUDE JAVASCRIPT//////////////////////////////////////////////////////////
    add_filter('woocommerce_default_address_fields', 'bbloomer_move_checkout_fields_woo_team');
    function bbloomer_move_checkout_fields_woo_team($fields)
    {
        $fields['state']['priority'] = 70;
        $fields['city']['priority'] = 80;
        return $fields;
    }

    add_filter('woocommerce_checkout_update_order_review', 'clear_wc_shipping_rates_cache_team');
    function clear_wc_shipping_rates_cache_team()
    {
        $packages = WC()->cart->get_shipping_packages();

        foreach ($packages as $key => $value) {
            $shipping_session = "shipping_for_package_$key";
            unset(WC()->session->$shipping_session);
        }
    }

    add_action('admin_menu', 'register_team_shipping_subpage');
    function register_team_shipping_subpage()
    {
        add_submenu_page(
            'safealternative-menu-content',
            'Team - Livrare',
            'Team - Livrare',
            'manage_woocommerce',
            'team_redirect',
            function () {
                wp_safe_redirect(safealternative_redirect_url('admin.php?page=wc-settings&tab=shipping&section=team'));
                exit;
            }
        );
    }
} // end if
