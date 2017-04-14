<!DOCTYPE html>
<html>
    <head>
        <title>Events in Toronto</title>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
        <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://unpkg.com/vue-material@0.7.1/dist/vue-material.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="wrapper" id="app">
            <md-checkbox v-on:change="filterFree" class="md-primary">Free Events</md-checkbox>
                <label for="date">Select a Date</label>
                <md-select name="date" id="date" v-model="selectedDate">
                    <md-option value="">Select Event by Date</md-option>
                    <md-option v-for="date in dates" :value="date">{{ date }}</md-option>
                </md-select>
            <h3>Found {{ activeEvents.length }} for you</h3>
            <div>
                <md-button class="md-raised" @click.native="changeView('list')">List</md-button>
                <md-button class="md-raised md-primary" @click.native="changeView('map')">Map</md-button>
            </div>
            <section v-if="view == 'list'">
                <div class="event" v-for="event in activeEvents" v-on:click="displayEvent(event)">
                    <md-card>
                        <md-card-area md-inset>
                            <md-card-media>
                                <!--<img class="event__image" :src="event.thumbImage" alt="" />-->
                                <div class="event__image" :style="{ 'background-image': 'url(' + event.thumbImage + ')' }" ></div>
                            </md-card-media>
                            <md-card-header>
                                <h2 class="md-title">{{ event.name.substring(0, 20) }}</h2>
                                <div class="md-subhead">
                                    <md-icon>location_on</md-icon>
                                    <span>Free: {{ event.freeEvent }}</span>
                                </div>
                                <div class="md-subhead">
                                    <md-icon>access_time</md-icon>
                                    <span>{{ event.startDate }} to {{ event.endDate }}</span>
                                </div>
                            </md-card-header>
                        </md-card-area md-inset>
                    </md-card>
                </div>
                <div class="clear"></div>
            </section>
            <section id="map" v-if="view == 'map'">
                
            </section>
        </div>
    </body>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCUfAQlAr-YR9De_ONa1reKPLA2xWuWm8&library=place"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/vue.resource/1.2.0/vue-resource.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/vue-material@0.7.1"></script>
    <script type="text/javascript" src="index.js"></script>
</html>