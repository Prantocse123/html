<?php
    if (isset($_POST['save'])){
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname= "ratingsystem";
    $conn = new mysqli_connect($server,$username, $password,$dbname);
    //$con = mysqli_connect( server: "localhost" , username: "root", passwd: "", dbname: "ratingsystem" );
    $uID = $conn->real_escape_string($_POST['uID']);
    $ratedIndex = $conn->real_escape_string($_POST['ratedIndex']);
    $ratedIndex++;

    if (!$uID){
    $conn->query("INSERT INTO stars (ratedIndex) VALUES ('$ratedIndex')");
    $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
    $uData = $sql->fetch_assoc();
    $uID= $uData['id'];
    }else
    $conn->query( "UPDATE stars SET ratedIndex='$ratedIndex' WHERE id='$uID'");

    exit(json_encode(array('id' =>$uID)));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ratingsystem</title>
    <script src="https://kit.fontawesome.com/6d8b97862a.js" crossorigin="anonymous"></script>
</head>
<body>
    <div align="center" style="background: Gray; padding: 50px;">
    <i class="fa fa-star fa-2x" data-index="0"></i>
    <i class="fa fa-star fa-2x" data-index="1"></i>
    <i class="fa fa-star fa-2x" data-index="2"></i>
    <i class="fa fa-star fa-2x" data-index="3"></i>
    <i class="fa fa-star fa-2x" data-index="4"></i>
    </div>

    <script src="http://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

var ratedIndex = -1, uID = 0;

$(document).ready(function (){ 
resetStarColors();

if(localStorage.getItem('ratedIndex')!=null){
setStars(parseInt(localStorage.getItem('ratedIndex')));
uID= localStorage.getItem('uID');
}

$('.fa-star').on('click',function(){
    ratedIndex=parseInt($(this).data('index'));
    localStorage.setItem('ratedIndex', ratedIndex);
    saveToTheDB();
});

$('.fa-star').mouseover(function(){
    resetStarColors(); 

    var currentIndex = parseInt($(this).data('index'));

    setStars(currentIndex);
});

$('.fa-star').mouseleave(function(){
    resetStarColors();

    if (ratedIndex != -1)
    setStars(ratedIndex);
});
});

function saveToTheDB(){
    $.ajax({
        url: "index.php",
        method: "POST",
        dataType: 'json',
        data: {
            save: 1,
            uID: uID,
            ratedIndex: ratedIndex
        }, success: function(r){
uID = r.id;
localStorage.setItem('uID', uID);
        }
    })
}

function setStars(max){
    for (var i=0; i<= ratedIndex; i++)
    $('.fa-star:eq('+i+')').css('color' , 'maroon');
}
function resetStarColors() {
    $('.fa-star').css('color', 'white');
}
</script>
</body>
</html>