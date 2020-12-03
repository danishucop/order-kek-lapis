<?php $this->load->view('include/header');?>

  <div class="container">
<br>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
            Add
            </button>
<br>
<br>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="<?php echo site_url("Menucontroller/add") ?>" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Menu Name</label>
                        <input type="text" class="form-control" name="name" aria-describedby="emailHelp">  
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Price</label>
                        <input type="text" class="form-control" name="price" aria-describedby="emailHelp">
                        
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Ingredient</label>
                        <input type="text" class="form-control" name="ingredient" aria-describedby="emailHelp">
                        
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="<?php echo site_url('Menucontroller')?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                    </form>
                    </div>
                    </div>
                    </div>
                    </div>
                   
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">id</th>
                            <th scope="col">Menu Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Ingredient</th>
                            <th scope="col">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                          
                            foreach($result as $row) {
                            
                            ?>
                            <tr class="table-primary">
                            <th scope="row"><?php echo $row->id; ?></th>
                            <td><?php echo $row->name; ?></td>
                            <td>RM <?php echo $row->price; ?></td>
                            <td><?php echo $row->ingredient; ?></td>
                            <td><a href="<?php echo site_url('Menucontroller/update');?>/<?php echo $row->id;?>">Edit</a> |
                            <a href="<?php echo site_url('Menucontroller/delete');?>/<?php echo $row->id;?>">Delete</a></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>   
                </div>
                </body>
                </html>
