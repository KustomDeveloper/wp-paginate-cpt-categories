/*
*  Wp Cpt Categories
*/


//Components
const WpcptCat = {
    data() {
        return {
          posts: {
            postArray: undefined
          },
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

        fetchPosts() {
            fetch(`http://localhost/plugin-development/wp-json/wp/v2/books`)
                .then(res => res.json())
                .then(res => {
                    this.posts = res
                    this.completedPostsCall = true;
                })
        }
      },

      template: 
        `<div id='Wpcpt-Categories' class='container'>
    
            <div v-if="completedPostsCall">
              <ul>
                  <li v-for="item in posts">{{ item.title.rendered }}</li>
              </ul>
            </div>
        </div>`
     ,
      mounted() {
        this.fetchPosts();
      }
}

RootComponent = {
    components: {
      WpcptCat,
    },
  
}

const app = Vue.createApp(RootComponent);
app.mount("#vue-app");
