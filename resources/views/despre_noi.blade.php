@extends('layouts.app')

@section('content')
    <!-- ======= About Section ======= -->

    <section class="slice slice-lg pt-lg-6 pb-0 pb-lg-6 bg-section-secondary">
        <div class="container">



        
            <!-- Title -->
            <!-- Section title -->
            <div class="row mb-5 justify-content-center text-secondary">
                <div class="col-lg-12">
                    <h2 class=" mt-6 text-center text-info">Ne-am unit din pasiune pentru programarea in mediul eCommerce.</h2>
                    <div class="mt-6">
                        <p >Am inceput pe drumul programarii cu sperante mari pentru a creste activitatea eCommerce in Romania. Ne-am unit pentru ca suntem siguri pe cunostintele noastre in domeniu, pentru credinta noastra intr-un viitor in care vanzatorii din Romania vor opta numai la comertul electronic.</p>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div class="row mt-5">
                <div class="col-md-4 ">
                    <div class="card">
                        <div class="card-body pb-5">
                            <div class="pt-4 pb-5">
                                <img src="{{ asset('images/curier.png' ) }}" class="mx-auto d-block rounded" style="height: 150px;" alt="Illustration" />
                            </div>
                            <h5 class="h4 lh-130 mb-3  text-center text-info">Integrare servicii curierat</h5>
                            <p class="text-muted mb-0">Unim afacerea ta online cu cele mai mari companii de curierat.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="card">
                        <div class="card-body pb-5">
                            <div class="pt-4 pb-5">
                                <img src="{{ asset('images/ecommerce.png' ) }}" class="mx-auto d-block rounded" style="height: 150px;" alt="Illustration" />
                            </div>
                            <h5 class="h4 lh-130 mb-3  text-center text-info">Platforme Ecommerce</h5>
                            <p class="text-muted mb-0">Dezvoltam module pentru cele mai populare platforme Ecommerce.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="card">
                        <div class="card-body pb-5">
                            <div class="pt-4 pb-5">
                                <img src="{{ asset('images/api.png' ) }}" class="mx-auto d-block rounded" style="height: 150px;" alt="Illustration" />
                            </div>
                            <h5 class="h4 lh-130 mb-3  text-center text-info">Dezvoltare API</h5>
                            <p class="text-muted mb-0">Dezvoltam API-uri pentru interconectarea diverselor sisteme din mediul on-line.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection