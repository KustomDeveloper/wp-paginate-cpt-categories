/*
*  Vue Boilerplate 
*/


//Components
const WeatherApi = {
    props: ['apikey'],
    data() {
        return {
          information: undefined,
          posts: {
            postArray: undefined
          },
          completedWeatherCall: false,
          completedPostsCall: false,
        }
      },
      computed: {
        //Example Computed Property

        // mock function that converts kelvin to fahrenheit
        // getFahrenheit() {
        //    return convertToFahrenheit(this.information);
        // }
      },
      methods: {
        fetchWeather() {
          fetch(`http://api.openweathermap.org/geo/1.0/direct?q=London&limit=1&appid=${this.apikey}`)
            .then(res => res.json())
            .then(res => {

              fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${res[0].lat}&lon=${res[0].lon}&appid=${this.apikey}`)
                .then(response => response.json())
                .then(response => {

                    this.information =  Math.ceil((response.main.temp - 273.15) * 9/5 + 32);
                    this.completedWeatherCall = true;

                }).catch(function(error) {
                    console.error('Weather Report Error:', error);
                })
            }).catch(function(error) {
              console.error('LatLong Error:', error);
            })
        },

        fetchPosts() {
            fetch(`http://localhost/plugin-development/wp-json/wp/v2/posts`)
                .then(res => res.json())
                .then(res => {
                    this.posts = res
                    this.completedPostsCall = true;
                })
        }
      },

      template: 
        `<div id='weather-plugin' class='container'>

            <div v-if="completedWeatherCall">
              <div class='alert alert-primary' role='alert'>
                  Currently the temp in London is: {{information}}
              </div>
            </div>
    
            <div v-if="completedPostsCall">
              <ul>
                  <li v-for="item in posts">{{ item.title.rendered }}</li>
              </ul>
            </div>
        </div>`
     ,
      mounted() {
        this.fetchWeather();
        this.fetchPosts();
      }
}

RootComponent = {
    components: {
        WeatherApi,
    },
  
}

const app = Vue.createApp(RootComponent);
app.mount("#vue-app");
