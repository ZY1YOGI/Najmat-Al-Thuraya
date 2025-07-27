<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // $table->foreignIdFor(App\Models\Phone::class);
            $table->string('imei')->unique();

            $table->tinyInteger('color');
            $table->tinyInteger('storage');
            $table->tinyInteger('country');
            $table->tinyInteger('battery');
            $table->tinyInteger('sim_card');

            $table->json('problems')->nullable();


            $table->integer('price');
            $table->integer('price_sell')->nullable();

            $table->text('description');

            $table->tinyInteger('status');


            $table->foreignIdFor(App\Models\Customer::class, 'customer_buy_id');
            $table->foreignIdFor(App\Models\Customer::class, 'customer_sell_id')->nullable();
            $table->timestamps();
        });


        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\PhoneModel::class);
            $table->string('imei')->unique();
            $table->tinyInteger('color');
            $table->tinyInteger('storage');
            $table->tinyInteger('country');
            $table->tinyInteger('battery');
            $table->tinyInteger('sim_card');
            $table->json('problems')->nullable();


            $table->integer('price');
            $table->integer('price_sell')->nullable();

            $table->text('description')->nullable();

            $table->tinyInteger('status');


            $table->foreignIdFor(App\Models\Customer::class, 'customer_buy_id');
            $table->foreignIdFor(App\Models\Customer::class, 'customer_sell_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('phones');
    }
};
