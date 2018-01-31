<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Add money</h4>
            </div>
            <div class="card-content">
                <div class="input-group col-md-12">
                    <input type="text" class="form-control" placeholder="Username">
                </div>
                <div class="input-group col-md-4">
                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Amount (Example: 7.60€)">
                </div>
                <div class="input-group col-md-12">
                    <textarea type="text" name="message" class="form-control" placeholder="Tip text" id="comment"
                              rows="3"></textarea>
                </div>
                <div class="input-group-btn">
                    <input type="button" data-background-color="blue" class="btn pull-right" value="GO!">
                </div>
            </div>
        </div>
    </div>
</div>