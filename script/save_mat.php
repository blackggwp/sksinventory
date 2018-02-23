<?php 
    include 'conn.php';
    
    
    $sql = 'INSERT INTO matmg 
    			([MAT_CODE],[MAT_QTY],[PLANT],[SAVED_BY],[SAVED_DATE])
			VALUES (\'11121231\',\'999\',\'1001\',\'Black\',\'2016-09-09\')';
    $ins = $conn->query($sql);
    if ($ins) {
    	echo "success";
    }
?>