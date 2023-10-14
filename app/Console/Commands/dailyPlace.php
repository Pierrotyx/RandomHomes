<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class dailyPlace extends Command
{
    protected $signature = 'dailyPlace';
    protected $description = 'Insert data into the dailyPlace table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $results = DB::table( 'dailyPlace' )
            ->select( 'address' )
            ->get()
        ;
        
        $newDay = DB::table('dailyPlace')
            ->latest('id')
            ->first()->day + 1
        ;
        
        $addresses = array_column( json_decode(json_encode($results), true), 'address' );
        foreach( [ 3, 5, 10 ] as $num )
        {
            $randomHomes = DB::table('homes')
                ->select('homes.*')
                ->whereIn('id', function ($query) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('homes')
                        ->where('type', '=', 'ForSale')
                        ->groupBy('address');
                })
                ->whereNotIn('address', $addresses)
                ->where('price', '<', 10000000)
                ->where('price', '>', 50000)
                ->where('bedrooms', '>', 0)
                ->where('bathrooms', '>', 0)
                ->limit($num)
                ->inRandomOrder()
                ->get()
            ;

           foreach( $randomHomes as $home )
           {
               $addresses[] = $home->address;
                DB::table('dailyPlace')->insert([
                    'type' => 'place' . $num,
                    'home' => json_encode( $home->id ),
                    'address' => json_encode( $home->address ),
                    'day' => $newDay
                ]);
           }

            $this->info('Data inserted into dailyPlace table successfully for place' . $num . '.'  );
        }
    }
}
