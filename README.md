wheelzo
========
- Carpooling for the kids of uWaterloo
- Production: [wheelzo.com](https://wheelzo.com)
- Staging: [staging.wheelzo.com](http://staging.wheelzo.com)
    - FB notifications do not send out on staging
    - Do whatever you please on this dummy site

## API - Version 2 
#### Endpoint prefix: `/api/v2`

- GET /users
    - Returns a list of all users mapped by user id
    - Each user contains `name`, `facebook_id`, `score`
    - DONE

- GET /rides
    - Returns a list of active rides mapped by ride id
    - Each ride contains ride details, `comments`, `passengers`
    - DONE

- GET /rides/me
    - Returns a list of personal rides mapped by ride id
    - Each ride contains ride details, `comments`, `passengers`
    - DONE

- POST /rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with ride details
    - Creates a ride using the specified details
    - DONE

- DELETE /rides/index/{{ ride_id }}
    - Must be logged in to use this endpoint
    - Deletes the ride associated with specified ride id
    - DONE

- POST /comments
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with comment details
    - Creates a `comment` for the specified `rideID`
    - DONE

- POST /rrequests
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with ride request details
    - Creates a ride request using the specified details
    - DONE

- DELETE /rrequests/index/{{ rrequest_id }}
    - Must be logged in to use this endpoint
    - Deletes the ride request associated with specified ride request id
    - DONE

- POST /points
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `receiver_id`
    - Gifts the specified receiving user with a street cred
    - DONE

- GET /reviews?receiver_id={{ user_id }}
    - Returns an array of review objects for a specified user
    - Each object contains `review_id`, `giver_id`, `review`, and `last_updated`
    - DONE

- POST /reviews
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `receiver_id` and `review`
    - Creates a review for the receiving user from the logged in user
    - DONE

- DELETE /reviews/index/{{ review_id }}
    - Must be logged in to use this endpoint
    - Deletes the review associated with specified review id
    - DONE

- POST /user_rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `rideID` and `passengerID`
    - Assigns the specified passenger to the specified ride
    - Can only be executed by the driver for the ride
    - DONE

- PUT /user_rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `user-rideID` and `passengerID`
    - Re-assigns the specified user-ride assignment to the specified passenger
    - Can only be executed by the driver for the ride
    - DONE

- DELETE /user_rides/index/{{ user_ride_id }}
    - Must be logged in to use this endpoint
    - Deletes the user-ride assignment associated with specified id
    - DONE

- POST /feedbacks
    - Creates a feedback message in the database
    - Accepts a JSON post parameter with `message` and `email`
    - DONE