<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Edit Product</h4>
            </div>
            <div class="card-content">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Name in System</label>
                                <input name="systemname" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Display Name</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Price</label>
                                <input name="price" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" data-background-color="<?= $db->getConfig()['color'] ?>"
                            class="btn btn-primary pull-right">Update Product
                    </button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>