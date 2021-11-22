<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si bookurier.png',
            'title'         =>  'Bookurier & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
        
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si fan courier.png',
            'title'         =>  'FAN Courier & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
        
        
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si nemo.png',
            'title'         =>  'Nemo Express & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
        
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si urgent cargus.png',
            'title'         =>  'Urgent Cargus & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
        
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si posta romana.png',
            'title'         =>  'Posta Romana & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
        $product = new Product([
            'imagePath'     =>  'images/woocommerce si gls curier.png',
            'title'         =>  'GLS  & WooCommerce',
            'description'   => 'Compatibil WooCommerce 4.2.0',
            'price'         => 10
        ]);
        
        $product->save();
        
    }
}
