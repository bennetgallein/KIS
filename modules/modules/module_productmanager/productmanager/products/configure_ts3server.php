<style>
    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="card-header" data-background-color="<?= $db->getConfig()['color'] ?>">
                        <h4 class="title text-center">Konfigurieren</h4>
                    </div>
                    <div class="card-content">
                        <div class="col-md-9">
                            <h3><b>Infos:</b></h3>
                            <table class="table">
                                <tr>
                                    <td>Filetransfer:</td>
                                    <td>Free</td>
                                </tr>
                                <tr>
                                    <td>Setup:</td>
                                    <td>Sofort</td>
                                </tr>
                                <tr>
                                    <td>Anbindung:</td>
                                    <td>bis zu 1Gbit</td>
                                </tr>
                                <tr>
                                    <td>inklusive</td>
                                    <td> DDos Protection</td>
                                </tr>
                                <tr>
                                    <td>Unlimited</td>
                                    <td>Traffic</td>
                                </tr>

                            </table>
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="sel1">Abrechnungszeitraum:</label>
                                            <select class="form-control calculate" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="slidecontainer">
                                            <label for="myRange">Slots:</label>
                                            <input type="range" min="10" max="350" value="10" step="1" class="slider" id="myRange">
                                            <p>Current slots: <span id="demo"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <div class="card-content text-center">
                                <table class="table" style="font-size: 0.9em;">
                                    <tr>
                                        <td>Name</td>
                                        <td>Price</td>
                                    </tr>
                                    <tr>
                                        <td id="server">TS³-Server</td>
                                        <td id="serverprice">0.00€</td>
                                    </tr>
                                    <tr>
                                        <td id="slots">Slots: 10</td>
                                        <td id="slotsprice">0.00€</td>
                                    </tr>
                                    <tr class="info">
                                        <td>Monatlich</td>
                                        <td>price</td>
                                    </tr>
                                </table>
                                <h3>Zu bezahlen:</h3>
                                <h4><b>
                                        <div id="item-price">price</div>
                                    </b></h4>
                                <hr>
                                <button type="submit" class="btn" data-background-color="blue">Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
        //$("myRange").val(this.value);
        $("input[type=range]").val(slider.value);
        console.log(slider.value);
    }
</script>