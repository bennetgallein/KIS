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
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="sel1">Abrechnungszeitraum:</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-center">Server konfigurieren</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Hostname</label>
                                            <input name="firstname" value="" type="text"
                                                   class="form-control" placeholder="servername.yourdomain.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Root-Passwort</label>
                                            <input name="lastname" value="" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-center">Optionen</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">RAM</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">vCores</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">SSD-Speicher</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">IPs</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-center">Weiteres</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="sel1">Betriebssystem</label>
                                            <select class="form-control" id="sel1">
                                                <option>#</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <div class="card-content text-center">
                                <table class="table" style="font-size: 0.9em;">
                                    <tr>
                                        <td>vServer | FEE</td>
                                        <td>13,37$</td>
                                    </tr>
                                    <tr>
                                        <td>RAM: 512MB</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>vCores: 1 vCore</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>SSD-Speicher: 10GB</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr>
                                        <td>IPs: 1 IP</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr class="info">
                                        <td>Einrichtungsgebühren:</td>
                                        <td>0,00$</td>
                                    </tr>
                                    <tr class="info">
                                        <td>vierteljährlich</td>
                                        <td>13,47$</td>
                                    </tr>
                                </table>
                                <h3>Zu bezahlen:</h3>
                                <h4><b>13,47$</b></h4>
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