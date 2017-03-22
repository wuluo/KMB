<ul class="pagination">
  <li><span>共 <?php echo $pagination->total;?> 条数据 </span></li>
  <?php
    if($pagination->current == 1) {
      echo '<li><span><i class="glyphicon glyphicon-step-backward"></i></span></li>';
      echo '<li><span><i class="glyphicon glyphicon-backward"></i></span></li>';
    } else {
      echo '<li><a href="'.$pagination->URL($pagination->first).'"><i class="glyphicon glyphicon-step-backward"></i></a></li>';
      echo '<li><a href="'.$pagination->URL($pagination->previous).'"><i class="glyphicon glyphicon-backward"></i></a></li>';
    }
    $previousPass = $pagination->current - 5;
    $nextPass = $pagination->current + 5;
    $begin = ($previousPass > 0) ? $previousPass : 1;
    $end = ($nextPass < $pagination->max) ? $nextPass : $pagination->max;
    while($begin <= $end) {
      if($begin == $pagination->current) {
        echo '<li class="active"><a>'. $begin .'</a></li>';
      } elseif($begin == $previousPass || $begin == $nextPass) {
        echo '<li><span>...</span></li>';
      } elseif($begin > $previousPass && $begin < $nextPass) {
        echo '<li><a href="'.$pagination->URL($begin).'">'. $begin .'</a></li>';
      }
      $begin++;
    }
    if($pagination->current == $pagination->max) {
      echo '<li><span><i class="glyphicon glyphicon-forward"></i></span></li>';
      echo '<li><span><i class="glyphicon glyphicon-step-forward"></i></span></li>';
    } else {
      echo '<li><a href="'.$pagination->URL($pagination->next).'"><i class="glyphicon glyphicon-forward"></i></a></li>';
      echo '<li><a href="'.$pagination->URL($pagination->last).'"><i class="glyphicon glyphicon-step-forward"></i></a></li>';
    }
  ?>
</ul>
