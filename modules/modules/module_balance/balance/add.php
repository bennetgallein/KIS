<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                <h4 class="title">Add balance</h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group label-floating">
                            <label class="control-label">Amount</label>
                            <input name="amount" value="" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <button type="submit" class="btn btn-default" data-background-color="<?= $db->getConfig()['color'] ?>"><i class="fab fa-paypal fa-lg"></i> Paypal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>