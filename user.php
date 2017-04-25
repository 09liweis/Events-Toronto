<?php
include 'template/header.php';
?>
        <div class="row">
            <div class="col right s12 m4">
                <p>
                    <input type="checkbox" class="filled-in" id="free" />
                    <label for="free">Free Event</label>
                </p>
                <p>
                    <input type="checkbox" class="filled-in" id="long-run" />
                    <label for="long-run">Hide long-running events</label>
                </p>
                <div id="datepicker"></div>
                <input id="date" type="hidden" value="<?=date('Y-m-d')?>" />
            </div>
            <div id="events" class="col s12 m8">
            </div>
        </div>
        
        <div id="detail" class="modal">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12 m4 l4" id="image">
                        <img src="images/events.jpg" class="responsive-img" />
                    </div>
                    <div class="col s12 m8 l8">
                        <h4 id="event_name"></h4>
                        <p id="event_description"></p>
                    </div>
                </div>
                <div class="row" id="detailMap">
                    
                </div>
            </div>
        </div>
<?php
include 'template/footer.php';
?>