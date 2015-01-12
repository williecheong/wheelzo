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
    - Returns an array of all users
    - Each user contains `name`, `facebook_id`, `score`

- GET /users/session
    - Returns an object containing `user_id` and `facebook_url`
    - If `user_id` is 0, there is no session and url is for login
    - If `user_id` is > 0, a session is active and url is for logout

- GET /users/me
    - Returns an array of users that are currently logged in
    - This is of course, an array of either zero or one object

- GET /users?id={{ user_id }}
    - Returns an array of users that have the specified user id
    - This is of course, an array of either zero or one object

- GET /users?facebook_id={{ facebook_id }}
    - Returns an array of users that have the specified facebook id
    - This is of course, an array of either zero or one object

- GET /rides
    - Returns an array of active rides (departure > 12am today)
    - Each ride object contains the details of the corresponding ride
    - Comments must now be retrieved seperately using the ride_id
    - The same goes for passenger assignments (user_rides)

- GET /rides/me
    - Returns an array of personal rides
    - Each ride contains ride details, `comments`, `passengers`
    
- POST /rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with ride details
    - Creates a ride using the specified details
    - Specify params as such:
```json
{   
    "origin" : "Waterloo", 
    "destination" : "Toronto",
    "departureDate" : "2014-09-23",
    "departureTime" : "00:00:00",
    "capacity" : "2",
    "price" : "10",
    "dropOffs" : [
        "Milton",
        "Mississauga"
    ]
}
```
    
- DELETE /rides/index/{{ ride_id }}
    - Must be logged in to use this endpoint
    - Deletes the ride associated with specified ride id
    
- GET /comments?ride_id={{ ride_id }}
    - Returns an array of comments for the specified ride id
    - The returned array is already sorted in earliest comment first

- POST /comments
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with comment details
    - Creates a `comment` for the specified `rideID`
    - Specify params as such:
```json
{   
    "comment" : "Can I have a ride please?", 
    "rideID" : "522" 
}
```

- POST /rrequests
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with ride request details
    - Creates a ride request using the specified details
    - Specify params as such:
```json
{   
    "origin" : "Waterloo", 
    "destination" : "Toronto",
    "departureDate" : "2014-09-23",
    "departureTime" : "00:00:00"
}
```

- DELETE /rrequests/index/{{ rrequest_id }}
    - Must be logged in to use this endpoint
    - Deletes the ride request associated with specified ride request id

- POST /points
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `receiver_id`
    - Gifts the specified receiving user with a street cred
    - Specify params as such:
```json
{   
    "receiver_id" : "123"
}
```

- GET /reviews?receiver_id={{ user_id }}
    - Returns an array of review objects for a specified user
    - Each object contains `review_id`, `giver_id`, `review`, and `last_updated`

- POST /reviews
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `receiver_id` and `review`
    - Creates a review for the receiving user from the logged in user
    - Specify params as such:
```json
{   
    "receiver_id" : "123",
    "review" : "This driver is awesome!"
}
```

- DELETE /reviews/index/{{ review_id }}
    - Must be logged in to use this endpoint
    - Deletes the review associated with specified review id

- GET /user_rides?ride_id={{ ride_id }}
    - Returns an array of passenger assignments for the specified ride id

- POST /user_rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `rideID` and `passengerID`
    - Assigns the specified passenger to the specified ride
    - Can only be executed by the driver for the ride
    - Specify params as such:
```json
{   
    "rideID" : "522",
    "passengerID" : "123"
}
```

- PUT /user_rides
    - Must be logged in to use this endpoint
    - Accepts a JSON post parameter with a `user-rideID` and `passengerID`
    - Re-assigns the specified user-ride assignment to the specified passenger
    - Can only be executed by the driver for the ride
    - Specify params as such:
```json
{   
    "user-rideID" : "333",
    "passengerID" : "123"
}
```

- DELETE /user_rides/index/{{ user_ride_id }}
    - Must be logged in to use this endpoint
    - Deletes the user-ride assignment associated with specified id

- POST /feedbacks
    - Creates a feedback message in the database
    - Accepts a JSON post parameter with `message` and `email`
    - Specify params as such:
```json
{   
    "email" : "example@gmail.com",      // optional
    "message" : "I love wheelzo! It makes me happy..." 
}
```
