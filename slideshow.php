
      <?php
      $request = request("SELECT `data`, `id_p`, `time` FROM users LEFT JOIN photos ON users.id = photos.id_user WHERE user ='".$_SESSION['logged_in']."' OR mail='".$_SESSION['logged_in']."'");
      // if ($request->rowCount() > 0)
      // {
        $request = $request->fetchall();
        $k = 0;
        $id = "";
        foreach ($request as $key => $value) {
            if ($value["id_p"] != $id) {
                $k++;
                $img = "Save/".$value['data'].".jpeg";
                $id = $value['id_p'];
                if ($value['data'] != "")
                {
                    echo'
                    <a href="http://localhost:8080/img.php?id='.$id.'">
                        <label for="id'.$k.'">
                            <img src="'.$img.'">
                        </label>
                    </a>';
                }
            }
// }
        }
      ?>

<!--             <a href="site.com/img?id="'.$id.'>
              <label for="id'.$k.'">
                <img src="'.$img.'">
              </label>
              <img src="'.$img.'">
            </a>';
 -->