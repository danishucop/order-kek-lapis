<?php $this->load->view('include/header');?>
<br>
<br>
  <form method="post" action="<?php echo site_url('Menucontroller/update_process')?>/<?php echo $row->id; ?>">
  <div class="container">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Menu Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $row->name;?>"aria-describedby="emailHelp">  
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Price (RM)</label>
                        <input type="text" class="form-control" name="price"  value="<?php echo $row->price;?>"aria-describedby="emailHelp">
                        
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Ingredient</label>
                        <input type="text" class="form-control" name="ingredient"  value="<?php echo $row->ingredient;?>" aria-describedby="emailHelp">

                    <br>
                    <br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="<?php echo site_url('Menucontroller')?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                    </form>
</div>
   
  </body>
</html>