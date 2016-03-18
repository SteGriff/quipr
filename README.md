# quipr

*I reserve the Copyright to the name 'quipr'*

This is a failed experiment in design patterns, to implement a twitter-like service.

## Design ideas

 * File-based data store
 * Use as many file system concepts as possible
 * No central code implementation; all users are independent nodes

## Wins

 * Easy for user-node-code to reference its component parts using relative paths
 * Users you're following are stored as symlinks and that's just cool

## Screw-ups

 * It's difficult to get one user node to request data from another
 * `quipCache` can only be updated when viewing a user's direct feed, so it doesn't get refreshed when loading your (aggregated) stream
