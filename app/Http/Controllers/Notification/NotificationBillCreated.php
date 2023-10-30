<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class NotificationBillCreated extends Controller
{
    
    public function test (){
        
        try {
            //code...
            
            $data = Student::with(['grade', 
            'relationship', 
            'bill' => function ($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uniform')
                ->where('paidOf', false) 
                ->first();
            }
            ])
            ->whereHas('bill', function($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uniform')
                ->where('paidOf', false);
            })
            ->get();


            return $data;

        } catch (Exception $err) {
            return $err;
        }

    }

    public function paket() {


        try {
            //code...
            
            $data = Student::with(['grade', 
            'relationship', 
            'bill' => function ($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Paket')
                ->where('paidOf', false) 
                ->first();
            }
            ])
            ->whereHas('bill', function($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Paket')
                ->where('paidOf', false);
            })
            ->get();

            //nanti kirim email disini 
            // return $data;

            info('success create notification paket at ' . now());
            
        } catch (Exception $err) {

            info('error create notification paket : ' . $err);

        }
    }



    public function uniform() {
        
         try {
            //code...
            
            $data = Student::with(['grade', 
            'relationship', 
            'bill' => function ($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uniform')
                ->where('paidOf', false) 
                ->first();
            }
            ])
            ->whereHas('bill', function($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uniform')
                ->where('paidOf', false);
            })
            ->get();

            // Kirim email disini
            // return $data;
    
            info('Success create notification uniform at ' . now());

        } catch (Exception $err) {
            
                info('Error create notification uniform :' . $err);
        }
    }


    public function feeRegister(){
        
        try {
            //code...
            
            $data = Student::with(['grade', 
            'relationship', 
            'bill' => function ($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uang Gedung')
                ->where('paidOf', false) 
                ->first();
            }
            ])
            ->whereHas('bill', function($query) {
                
                $query
                ->whereDate('created_at', '<', Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'))
                ->whereDate('created_at', '>', Carbon::now()->setTimezone('Asia/Jakarta')->subDays(1)->format('Y-m-d H:i:s'))
                ->where('type', 'Uang Gedung')
                ->where('paidOf', false);
            })
            ->get();


            info('Success create notification fee register at '. now());
            
        } catch (Exception $err) {

            info('Error create notification fee register at '. now());
        }

    }
}
