## BRashboard

Brashboard is a simple playground to prove existing skills about WebHook + simple FE rendering.
- Laravel 7.9.2
- GET / for dashboard
- POST /hook-me-baby-one-more-time for WebHook with several events:
initialise new client, add login event, add visitor event
- Vue 3.1.2 and Vuetify 2.2.26 as OnePager Dashboard (no interaction)


## Hints

**GET**
**/** for dashboard overview. Easy tiles are showing some data about certain clients

**POST**
**/api/hook-me-baby-one-more-time** for WebHook Endpoint.
Header always wants 
*Event*:[initialise_new_client, new_login, new_visitor] and
*Authorization*

In Folder *postman* is a collection to be played with.

## License

The BRashboard is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
