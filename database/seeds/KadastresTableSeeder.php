<?php

use Illuminate\Database\Seeder;

class KadastresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kadastres')->delete();
        
        \DB::table('kadastres')->insert(array (
            0 => 
            array (
                'id' => 804096,
                'name' => 'Staré Mesto',
                'city_district' => 1,
            ),
            1 => 
            array (
                'id' => 847755,
                'name' => 'Podunajské Biskupice',
                'city_district' => 2,
            ),
            2 => 
            array (
                'id' => 804274,
                'name' => 'Nivy',
                'city_district' => 3,
            ),
            3 => 
            array (
                'id' => 805343,
                'name' => 'Trnávka',
                'city_district' => 3,
            ),
            4 => 
            array (
                'id' => 805556,
                'name' => 'Ružinov',
                'city_district' => 3,
            ),
            5 => 
            array (
                'id' => 870293,
                'name' => 'Vrakuňa',
                'city_district' => 4,
            ),
            6 => 
            array (
                'id' => 804380,
                'name' => 'Vinohrady',
                'city_district' => 5,
            ),
            7 => 
            array (
                'id' => 804690,
                'name' => 'Nové Mesto',
                'city_district' => 5,
            ),
            8 => 
            array (
                'id' => 805866,
                'name' => 'Rača',
                'city_district' => 6,
            ),
            9 => 
            array (
                'id' => 805700,
                'name' => 'Vajnory',
                'city_district' => 7,
            ),
            10 => 
            array (
                'id' => 809985,
                'name' => 'Čunovo',
                'city_district' => 14,
            ),
            11 => 
            array (
                'id' => 822256,
                'name' => 'Jarovce',
                'city_district' => 15,
            ),
            12 => 
            array (
                'id' => 804959,
                'name' => 'Petržalka',
                'city_district' => 16,
            ),
            13 => 
            array (
                'id' => 853771,
                'name' => 'Rusovce',
                'city_district' => 17,
            ),
            14 => 
            array (
                'id' => 821438,
                'name' => 'Farná',
                'city_district' => 108,
            ),
            15 => 
            array (
                'id' => 821446,
                'name' => 'Ivanka pri Dunaji',
                'city_district' => 108,
            ),
            16 => 
            array (
                'id' => 872270,
                'name' => 'Zálesie',
                'city_district' => 555509,
            ),
        ));
        
        
    }
}
