<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Home</title>
<style type="text/css" media="all">
#wrapper{
  width: 960px;
  margin: 0px auto;
}
#sidebar{
  width: 200px;
  display:inline-block;
}
#content{
  width:700px;
  display:inline-block;
}
</style>
 
    <!-- โหลด jQuery จาก CDN ของ Google -->
    <script charset="utf-8" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 
  </head>
  <body>
    <div id="wrapper">
      <div id="sidebar">
        <ol>
          <li><a href="index.php">Page 1</a></li>
          <li><a href="page2.html">Page 2</a></li>
          <li><a href="page3.html">Page 3</a></li>
        </ol>
      </div>
      <!-- div ที่ต้องการอัพเดทเนื้อหาด้วย Ajax -->
      <div id="content">
        <h1>Home</h1>
        <p>Home Content</p>
      </div>
    </div>
    <script>
$(function(){
  /* เพิ่มฟังก์ชันที่จะเรียก Ajax เมื่อมีการคลิกลิงค์ที่อยู่ภายใต้ div ที่มี id="sidebar" */
  $('#sidebar').delegate('a', 'click', function(e){
    e.preventDefault();
    var link = this.href;
       
    /* ดึงเนื้อหาจากลิงค์ด้วย Ajax เมื่อผู้ใช้กดลิงค์ */
    $.get(link, function(res){
      /* อัพเดทเนื้อหาที่ได้จาก Ajax ไปที่ div ที่มี id="content" */
      $('#content').html(res);
      /* หลังจากอัพเดทเนื้อหาเสร็จ เปลี่ยน URL ของเบราว์เซอร์ */
      window.history.replaceState(null, null, link);
    });
  });
});
    </script>
  </body>
</html>