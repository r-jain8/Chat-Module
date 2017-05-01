<?php
include 'core_users.php';
session_start();
date_default_timezone_set('Asia/Kolkata');



if(isset($_SESSION['user'])){
    if($_SESSION['id']<$_SESSION['fid']){
    $file = $_SESSION['id']."_".$_SESSION['fid'];
    }
    else{
      $file = $_SESSION['fid']."_".$_SESSION['id'];
    }
?>
<script>
$("#myDropdown").empty();
var itemno = 1;
</script>
<?php
$cartitem = $client->query([
    'TableName' => 'SelectionCart',
    'KeyConditionExpression' => 'UsersId = :v_hash and DateAndTime >= :v_range',
    'ExpressionAttributeValues' =>  [
        ':v_hash'  => ['S' => $file],
        ':v_range' => ['S' => '12/02/2017 23:44:36.000000']
    ]
]);

foreach ($cartitem['Items'] as $item) {
  ?>
  <script type = "text/javascript">
    var mcode = "<?php echo $item['ProductCode']['S']; ?>";
    var pname = "<?php echo $item['ProductName']['S']; ?>";
    var mlink = "<?php echo $item['ProductLink']['S']; ?>";
    var mimage = "<?php echo $item['ProductThumbnail']['S']; ?>";
    var key;

    var mycart = document.getElementById("myDropdown");
    var aTagc = document.createElement('a');
    aTagc.setAttribute('href',mlink);
    aTagc.setAttribute('target', '_blank');
    aTagc.innerHTML = pname;
    var btn = document.createElement('button');
    btn.innerHTML = 'Remove Item';
    var img = document.createElement("img");
    img.setAttribute("src", mimage);
    aTagc.appendChild(img);
    var aTagd = document.createElement('div');
    aTagd.setAttribute('id',mcode);
    var span = document.createElement('span');
    span.innerHTML = itemno;
    aTagd.append(span);
    itemno++;
    btn.onclick = function(){
      key = $(this).closest("div").attr("id");
       $(this).closest('div').remove();
      myfunction();
    };
    aTagd.appendChild(aTagc);
    aTagd.appendChild(btn);
    mycart.appendChild(aTagd);
  </script>
  <?php
  }
  ?>
  <script>
  function myfunction()
    {
      itemno = 1;
      $('#myDropdown span').each(function(){
        this.innerHTML = itemno;
        itemno++;
    });

    $.ajax({
           type: "POST",
           url: 'deletecartitem.php',
           data: { key : key }
       });
    }
    </script>
    <?php
}
?>
