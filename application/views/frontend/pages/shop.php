<div style="margin-top: 100px;"></div>
    <div class="container">

      <div class="row">

        <div class="col-lg-3">

          <h1 class="my-4">Shop Name</h1>
          <div class="list-group">
            <a href="#" class="list-group-item">Category 1</a>
            <a href="#" class="list-group-item">Category 2</a>
            <a href="#" class="list-group-item">Category 3</a>
          </div>

        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">


          <?php foreach($result as $key => $val) : ?>
            <div class="row">
              <?php foreach($val as $row) : ?>
                <div class="col-lg-4 col-md-6 mb-4">
                  <div class="card h-100">
                    <a href="#"><img class="card-img-top img-responsive" src="<?php echo site_url("thumbs/images/product/".$row->images[0]->image_path."/300/300/".$row->images[0]->image_name); ?>" alt="" ></a>
                    <div class="card-body">
                      <h4 class="card-title">
                        <a href="#"><?php echo $row->product_name; ?></a>
                      </h4>
                      <h5><?php echo $row->price; ?></h5>
                      <p class="card-text"><?php echo $row->product_description; ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>

        </div>
        <!-- /.col-lg-9 -->

      </div>
      <!-- /.row -->

    </div>