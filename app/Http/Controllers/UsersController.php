<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Auth;
class UsersController extends Controller
{
        public function login()
        {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('appToken')->accessToken;
               //After successfull authentication, notice how I return json parameters
                return response()->json([
                  'success' => true,
                  'token' => $success,
                  'user' => $user
              ]);
            } else {
           //if authentication is unsuccessfull, notice how I return json parameters
              return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe pas correcte',
            ], 401);
            }
        }

         /**
     * Register api.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'phone' => 'required|size:10|regex:/(06)[0-9]{8}/',
          'email' => 'required|email|unique:user',
          'password' => 'required',
        ]);
        if ($validator->fails()) {
          return response()->json([
            'success' => false,
            'message' => $validator->errors(),
          ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $password=$input['password'];
        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
          'success' => true,
          'token' => $success,
          'user' => $user,
          'password' => $password
      ]);
    }

    public function logout(Request $res)
    {

      if (Auth::user()) {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
          'success' => true,
          'message' => 'Logout successfully'
      ]);
      }else {
        return response()->json([
          'success' => false,
          'message' => 'Unable to Logout'
        ]);
      }
     }





       function search($name){
        
        $doctors = DB::table('doctor')
        ->select(DB::raw("doctor.*,specialty.name as specialty"))
        ->join('specialty','doctor.Spe_id','=','specialty.id')
        ->where('city', 'LIKE', "%{$name}%")
        ->orWhere('firstname', 'LIKE', "%{$name}%")
        ->orWhere('doctor.name', 'LIKE', "%{$name}%")
        ->orWhere('specialty.name', 'LIKE', "%{$name}%")
        ->get();
      
                  ///$search2= $request->city;
                  ///$search3= $request->specialty;
                      
          
                      $user = Auth::user();
                        $success['token'] = $user->createToken('appToken')->accessToken;
                     return response()->json([
                      'success' => true,
                      'token' => $success,
                      'doctor' => $doctors,
                      
                  ]);
                    
                  ///$users = DB::table('users')
                  ///->select(DB::raw('count(*) as nm'))
                  //->where('id', 'LIKE', 1)
                  
                  //->get();


                    }

            function makeAppoin($id)
            {
                
              $iduser = Auth::user()->id;

              $idAppointment = DB::table('appointment')
              ->insertGetId(array('Pat_id' => "$iduser" , 'Doc_id' => "$id" , 'state' => 'request'));

              $Appointment = DB::table('appointment')->select('appointment.*')
              ->where('id', 'LIKE', "%{$idAppointment}%")
              ->get();
 
              $user = Auth::user();
              

                        $success['token'] = $user->createToken('appToken')->accessToken;
                     return response()->json([
                      'success' => true,
                      'token' => $success,
                      'id' => $id,
                      'iduser' => $iduser,
                      'idAppointment' => $idAppointment,
                       'appointment' => $Appointment,
                    ]);



            }

         function checkProposition($id){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')->select('appointment.*')
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "proposition")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }
         function checkRequest($id){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')->select('appointment.*')
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "request")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }

         function checkAccept($id){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')->select('appointment.*')
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "accept")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }
              
         function checkRefuse($id){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')->select('appointment.*')
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "refuse")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }

         function yourDoctors(){
          $iduser = Auth::user()->id;


        $doctors = DB::table('appointment')
        ->select(DB::raw("doctor.*,specialty.name as specialty"))
        ->join('doctor','appointment.Doc_id','=','doctor.id')
        ->join('specialty','doctor.Spe_id','=','specialty.id')
        ->where('Pat_id', 'LIKE', "%{$iduser}%")
        ->where('state', 'not like', "NULL")
        ->distinct('id')
        ->get();
        

        
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
                'doctor' => $doctors,
             ]);



         }

         function isNewDoctor($id)

         {
          $iduser = Auth::user()->id;
          $result = false;

          $idDoc = DB::table('appointment')->select('appointment.Doc_id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->distinct('id')
              ->get(['Doc_id']);

               if(count($idDoc)== 0)
               {
                 $result = true;
               }

               $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
                'success' => true,
                'result' => $result,
                'id'=>$idDoc,
             ]);


          }
           

          function activeButtonMake($id){


            $iduser = Auth::user()->id;
          $result = false;

          $idDoc = DB::table('appointment')
              ->select('appointment.Doc_id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('Doc_id', 'LIKE', "%{$id}%")
              ->where('state', 'not like', "refuse")
              ->where('state', 'not like', "accept")
              ->where('state', 'not like', "annule")
              ->distinct('id')
              ->get(['Doc_id']);

               if(count($idDoc)== 0)
               {
                 $result = true;
               }

               $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
                'success' => true,
                'result' => $result,
                'id'=>$idDoc,
             ]);



          }

          function ButtonAccept($id){

            $user = Auth::user();
            DB::table('appointment')
                ->where('id', $id)
                ->update(['state' => "accept"]); 
                $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
                'success' => true,
                
             ]);


               
          }
               function ButtonAnnule($id){

            $user = Auth::user();
            DB::table('appointment')
                ->where('id', $id)
                ->update(['state' => "annule"]); 
                $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
                'success' => true,
                
             ]);


               
          }
          function ButtonRefuse($id){

            $user = Auth::user();
            DB::table('appointment')
                ->where('id', $id)
                ->update(['state' => "refuse"]); 
                $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
                'success' => true,
                
             ]);


               
          }

         
         
         
          function categories(){
          $categorie = DB::table('specialty')->select('specialty.*')->get();
          $user = Auth::user();

          $success['token'] = $user->createToken('appToken')->accessToken;
          return response()->json([
           'success' => true,
           'categorie' => $categorie,
          
          
          ]);

         }

         
        






         function isNewPatient()
         {
          $iduser = Auth::user()->id;
          $result = '';

          $id = DB::table('appointment')->select('appointment.Doc_id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->get(['Pat_id']);

               if(count($id)== 0)
               {
                 $result = 'vous avez pas faites aucun rendez-vous ';
               }

               $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'idUser' => $iduser,
               'user' => $user,
                'result' => $result,
             ]);




         }

         function appointmentUser()
         {
          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')
              ->select(DB::raw("doctor.firstname,doctor.name,doctor.photo,appointment.*,specialty.name as specialty"))
              ->join('doctor','appointment.Doc_id','=','doctor.id')
              ->join('specialty','doctor.Spe_id','=','specialty.id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
               ->get();
          $user = Auth::user();

          $success['token'] = $user->createToken('appToken')->accessToken;
          return response()->json([
            'success' => true,
            'token' => $success,
            'iduser' => $iduser,
             'appointment' => $Appointment,]);



         }



         function checkPropositionUser(){

          $iduser = Auth::user()->id;
          

              $Appointment = DB::table('appointment')
              ->select(DB::raw("doctor.firstname,doctor.name,doctor.photo,appointment.*,specialty.name as specialty"))
              ->join('doctor','appointment.Doc_id','=','doctor.id')
              ->join('specialty','doctor.Spe_id','=','specialty.id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "proposition")
              ->distinct('id')
              ->get()
              ->sortBy(function($col)
              {  return $col;
               })->values()->all();
             
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
                ///'bb' =>$sorted,
             ]);

           


         }
         function checkRequestUser(){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')
              ->select(DB::raw("doctor.firstname,doctor.name,doctor.photo,appointment.*,specialty.name as specialty"))
              ->join('doctor','appointment.Doc_id','=','doctor.id')
              ->join('specialty','doctor.Spe_id','=','specialty.id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->distinct('id')
              ->where('state', 'LIKE', "request")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }

         function checkAcceptUser(){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')
              ->select(DB::raw("doctor.firstname,doctor.name,doctor.photo,appointment.*,specialty.name as specialty"))
              ->join('doctor','appointment.Doc_id','=','doctor.id')
              ->join('specialty','doctor.Spe_id','=','specialty.id')
              ->where('Pat_id', 'LIKE', "%{$iduser}%")
              ->where('state', 'LIKE', "accept")
              ->distinct('id')
             ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }
              
         function checkRefuseUser(){

          $iduser = Auth::user()->id;

          $Appointment = DB::table('appointment')
          ->select(DB::raw("doctor.firstname,doctor.name,doctor.photo,appointment.*,specialty.name as specialty"))
          ->join('doctor','appointment.Doc_id','=','doctor.id')
          ->join('specialty','doctor.Spe_id','=','specialty.id')
          ->where('Pat_id', 'LIKE', "%{$iduser}%")
          ->distinct('id')
              ->where('state', 'LIKE', "refuse")
              ->get();
              $user = Auth::user();

              $success['token'] = $user->createToken('appToken')->accessToken;
              return response()->json([
               'success' => true,
               'token' => $success,
               'iduser' => $iduser,
                'appointment' => $Appointment,
             ]);

           


         }





















}










