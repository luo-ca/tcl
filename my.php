<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: ./login.html");
  exit;
}

if (isset($_SESSION["qx"]) && $_SESSION["qx"] != "admin") {
  header("Location: ./kh_home.php"); // 非管理员跳转到客户管理页面
  exit;
}

?>
<html>

<head>
  <title>用户管理系统</title>
  <meta charset="UTF-8">
  <script src="./jquery-3.4.1.min.js"></script>
  <script>
    function logout() { //监听注销按钮的点击事件。
      // 发送注销请求
      $.ajax({
        url: "./session_cz.php",
        method: "POST",
        success: function() { //请求成功后执行的回调函数
          location.href = "./login.html";
        }
      });
    }
  </script>
  <style>
    #head {
      margin: auto;
    }

    a {
      color: inherit;
      /* 继承父元素的颜色 */
      text-decoration: none;
      /* 移除下划线 */
    }

    td {
      text-align: center;
    }

    .tdys:hover {
      background-color: orange;
    }

    p {
      text-align: center;
    }

    /* .tdys a:hover{
            color: #FBFDFF;
        } */
        button {
        border: none;
        background-color: black;
        padding: 5px 10px;
        color: white;
        cursor: pointer;
        margin-right: 5px;
    }
  </style>


  <script>
    function displaypasswd($id) {
      // 获取输入元素
      inputElement = document.getElementsByName("passwd" + $id + "")[0];
      // 更改输入元素的类型
      if (inputElement.type === "password") {
        inputElement.type = "text";
      } else {
        inputElement.type = "password";
      }
    }

    // 头像上传
    function upimages() {
      fileupload = document.getElementById("file").files[0];
      filedata = new FormData();
      filedata.append('file', fileupload);

      $.ajax({
        type: 'post',
        url: './upload.php',
        data: filedata,
        processData: false,
        contentType: false,
        cache: false,
        success: function(data) {
          alert(data);
          if (data == "文件类型不正确") {
            document.getElementById("fil").innerHTML = "<font size='2'>错误类型</font>";
          } else if (data == "上传成功") {
            document.getElementById("fil").innerHTML = "<font color='green' size='3'>√</font>";
          } else {
            document.getElementById("fil").innerHTML = "<font size='2'>上传失败</font>";
          }
        }
      })
    }
  </script>
</head>

<body>
  <table id='head' border="0" width="100%" cellspacing="0" bgcolor="white">
    <tr>
      <!-- <td><img src="https://www.woniuxy.com/static/woniuopen/img/title-logo.png" alt="logo"></img></td> -->
      <td width="15%"></td>
      <td width="20%" class="tdys"><a href="" target="_blank">首页</a></td>
      <td width="20%" class="tdys"><a href="./home.php">用户管理</a></td>
      <td width="20%" class="tdys"><a href="./my.php" target="_blank">我的信息</a></td>
      <td width="10%"><img src="https://fanyunimage.oss-cn-hangzhou.aliyuncs.com/user//3552d933e84948538cf0e9a62f40abfe.jpg" width="40px" height="40px" style="border-radius: 50%;" alt="我是头像" align="center"></td>
      <td width="15%"></td>
    </tr>
  </table>
  <hr />
  <div>

    <?php
    include_once "db.php";
    // $conn = sqlconn();
    $username = $_SESSION["username"];
    // $sql = "SELECT * FROM userinfo WHERE username = '$username'";
    // $result = mysqli_query($conn, $sql);
    $resultzd = userdisplayzd($username);
    // 输出数据
    while ($row = mysqli_fetch_assoc($resultzd)) {
      echo "<p>" . $row['username'] . "</p>";
      echo "<p><img src='" . $row['touiages'] . "' width='40px' height='40px' style='border-radius: 50%;' alt='我是头像' align='center' onclick='upimages()'></p>";
      echo "<p>头像上传<br/><input type='file' id='file' accept='image/*'><button onclick='upimages()'>提交</button></p>";
      echo "<p><input type='password' name='passwd" . $row['id'] . "' class=" . $row['id'] . " value=" . $row['password'] . " enable>";
      echo "&nbsp;<button onclick='displaypasswd(" . $row['id'] . ")'>查看密码</button>";
      echo "</p>";
      echo "<p><button onclick='logout()'>注销登录</button></p>";
    }
    ?>

  </div>
</body>

</html>