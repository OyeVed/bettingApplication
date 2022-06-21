<?php

// import db connection
require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$phone_number = $_POST['phone_number'];
$password = md5($_POST['password']);
$email = $_POST['email'];
$full_name = $_POST['full_name'];
$referred_by = $_POST['referred_by'];


//Finding is the particular user already signed up
$sql = "SELECT * FROM user_table WHERE user_table.user_phonenumber=:phone_number";
$query = $con -> prepare($sql);
$query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    //to generate referral code
    function generate_code($length)
    {
        $str = "A1BC3D22EFG1Ha5Ib5bJK2fLf5M1Ns45O3z2P5vh9hQ7R94nSTUVjWX8yYZ".time();
        $str = str_shuffle($str);
        $str = str_split($str,1);
        $res = '';
        for ($i=0;$i<$length;$i++)
        {
            $res .= $str[rand(0,count($str)-1)];
        }
        return $res;
        
    }

    $is_code_unique = true;
    while($is_code_unique){
        $referral_code = generate_code(6);
        $sql = "SELECT referral_code FROM user_table WHERE referral_code = :referral_code";
        $query = $con -> prepare($sql);
        $query->bindParam(':referral_code', $referral_code, PDO::PARAM_STR);
        $query->execute();
        if($query->rowCount() === 0){
            $is_code_unique = false;
            $sql = "INSERT INTO 
            user_table (user_phonenumber, user_password, user_email, user_fullname, referral_code, referred_by) VALUES 
            ( :user_phonenumber, :user_password, :user_email, :user_fullname, :referral_code, :referred_by)";
            $query = $con -> prepare($sql);
            $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
            $query->bindParam(':user_password', $password, PDO::PARAM_STR);
            $query->bindParam(':user_email', $email, PDO::PARAM_STR);
            $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);
            $query->bindParam(':referral_code', $referral_code, PDO::PARAM_STR);
            $query->bindParam(':referred_by', $referred_by, PDO::PARAM_STR);
        
        
            if($query->execute()){
                $user = $query->fetchAll(PDO::FETCH_OBJ);
                $status = 200;
                $response = [
                    "msg" => "User created successfully"
                ];
            }else{
                $status = 203;
                $response = [
                    "msg" => "Internal Server Error - User creation failed"
                ];
            }
        
        }

    }
}else{

    $status = 203;
    $response = [
        "msg" => "Bad Request - User already exists"
    ];

}