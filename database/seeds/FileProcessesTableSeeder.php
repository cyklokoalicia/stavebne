<?php

use Illuminate\Database\Seeder;

class FileProcessesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('file_processes')->delete();
        
        \DB::table('file_processes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'saved',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'downloaded',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'OCRd',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'analyzed',
            ),
        ));
        
        
    }
}
