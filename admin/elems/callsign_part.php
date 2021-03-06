<form method="post">


<div class="row">
            <div class="col d-flex justify-content-center">
                <select name="callsign" id="callsign" required>
                    <?php 
                    foreach ($available_callsigns as $c) {
                        echo "<option>" . $c->label . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center"><button class="btn btn-success btn-pad" type="submit">Submit</button></div>
        </div>
        </form>