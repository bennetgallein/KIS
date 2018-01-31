<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" data-background-color="<?= $db->getConfig()['color']?>">
                <h4 class="title">Transactions</h4>
                <p class="category">These are your recent transactions.</p>
            </div>
            <div class="card-content table-responsive">
                <table class="table">
                    <thead class="text-primary">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Bought on</th>
                        <th>Dispatched to:</th>
                        <th>Order ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>23$</td>
                        <td>28-01-2018</td>
                        <td>Town</td>
                        <td>325C36E5345A</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-header" data-background-color="orange">
            <?= "100€" ?>
        </div>
        <div class="card-content">
            <h4 class="title">Daily Sales</h4>
            <p class="category"><span class="text-success"><i class="fa fa-long-arrow-up"></i> 55%  </span> increase in today sales.</p>
        </div>
        <div class="card-footer">
            <div class="stats">
                <i class="material-icons">access_time</i> updated 4 minutes ago
            </div>
        </div>
    </div>
</div>