
            <div class="row">
                <div class="col">
                    
                    <hr>
                    <h3 class="d-flex justify-content-center"><?=$question?><br></h3>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <ul class="d-flex justify-content-center">
                        
                                <li style="margin: 19px;padding: 0;"><?=$answer?><br></li>
                            
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <input type="range" class="slider w-25" min='0' max='<?=$points?>' value='0' step='1' name='A[]' oninput='UpdateElem(this)' id="RangeSlider">
                    <input type='text' style='width:20px'class='output' value='0' disabled >
                </div>
                
            </div>
