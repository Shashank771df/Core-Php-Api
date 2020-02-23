<?php
include "connect.php";
$response = array();
    if(isset($_GET['apicall'])){
        switch($_GET['apicall']){
            case 'signup':
                if(isTheseParametersAvailable(array('username','email_id','mobile_no','location','profile_image','password'))){
                $username = $_POST['username'];
                $email_id = $_POST['email_id'];
                $mobile_no = $_POST['mobile_no'];
                $location = $_POST['location'];
                $profile_image = $_POST['profile_image'];
                $password = md5($_POST['password']);


                $stmt = $obj->conn->prepare("SELECT id FROM end_users WHERE email_id = ?");
                $stmt->bind_param("s",$email_id);
                $stmt->execute();
                $stmt->store_result();

                if($stmt->num_rows > 0){
                    $response['error'] = true;
                    $response['message'] = 'User already registered';
                    $stmt->close();
                }else{
                    $stmt = $obj->conn->prepare("INSERT INTO end_users (username, email_id, mobile_no, location, profile_image, password) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $email_id, $mobile_no, $location, $profile_image, $password);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT  username, email_id, mobile_no, location, profile_image, password FROM end_users WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->bind_result($username, $email_id, $mobile_no, $location, $profile_image, $password);
                        $stmt->fetch();

                        $user = array(
                            'username'=>$username,
                            'email_id'=>$email_id,
                            'mobile_no'=>$mobile_no,
                            'location'=>$location,
                            'profile_image'=>$profile_image,
                            'password'=>$password
                        );

                        $stmt->close();

                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'User registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
            break;
            
            case 'login':

            if(isTheseParametersAvailable(array('email_id', 'password'))){

                $email_id = $_POST['email_id'];
                $password = md5($_POST['password']);
                $stmt = $obj->conn->prepare("SELECT * FROM end_users WHERE email_id = ? AND password = ?");
                $stmt->bind_param("ss",$email_id, $password);

                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){

                    $stmt->bind_result($id, $username, $email_id, $mobile_no, $location, $profile_image, $password);
                    $stmt->fetch();


                    $user = array(
                        'id'=>$id,
                        'username'=>$username,
                        'email_id'=>$email_id,
                        'mobile_no'=>$mobile_no,
                        'location'=>$location,
                        'profile_image'=>$profile_image,
                        'password'=>$password
                    );

                    $response['error'] = false;
                    $response['status'] = '1';
                    $response['message'] = 'Login successfull';
                    header('Content-type: multipart/form-data');
                    echo json_encode($response);

                }else{
                    $response['error'] = true;
                    $response['status'] = '0';
                    $response['message'] = 'Invalid email or password';
                    header('Content-type: multipart/form-data');
                    echo json_encode($response);
                }
              }
              break;

        case 'specialization':
        $sql="SELECT * FROM category WHERE status='1'";

        $query=mysqli_query($obj->conn,$sql);

        while($result=mysqli_fetch_assoc($query))
        {
            $category[] = array("category_id"=>$result['category_id'],
            "category_name"=>$result['category_name'],
            "icon"=>$result['icon']);
        }
        header('Content-type: multipart/form-data');
        echo json_encode(array('Items' => $category));
        break;


        default:
            $response['error'] = true;
            $response['message'] = 'Invalid Operation Called';
    }
}else{
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}


//json_decode(file_get_contents("php://input"));

 function isTheseParametersAvailable($params){

    foreach($params as $param){
        if(!isset($_POST[$param])){
            return false;
        }
    }
    return true;
 }
