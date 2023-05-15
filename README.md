# Posten API Proxy

Unofficial API Proxy script to get next mail delivery from Posten as JSON.

This API can be hosted on a webserver and allow you to use the data from Posten in other scripts/services, e.g. Home Assistant RESTful sensor.

This API uses the same (unofficial) API as the official tool at https://www.posten.no/levering-av-post.

Since the URL and API key changes frequently this script will get the URL and API key from the official site and then query the API using the details extracted and output the data.

## Usage

To use the script send a GET request to the php script with your `postcode` as payload. You will then get a JSON back with the following response. E.g. https://127.0.0.1/posten.php?postcode=0001

- `postentoday` `1` If mail will be delivered today (otherwise `0`)
- `delivery0` Next delivery date (index 0) in ISO 8601 format (e.g. 2023-01-01)
- `delivery1` Next delivery date after delivery0 (index 1) in ISO 8601 format (e.g. 2023-01-01)
- `delivery2` Next delivery date after delivery1 (index 2) in ISO 8601 format (e.g. 2023-01-01)
- `delivery3` Next delivery date after delivery2 (index 3) in ISO 8601 format (e.g. 2023-01-01)
- `delivery4` Next delivery date after delivery3 (index 4) in ISO 8601 format (e.g. 2023-01-01)
- `delivery0_formatted` Next delivery date (index 0) in `D j M Y` format (e.g. 1 Jan 2023)
- `delivery1_formatted` Next delivery date after delivery0 (index 1) in `D j M Y` format (e.g. 1 Jan 2023)
- `delivery2_formatted` Next delivery date after delivery1 (index 2) in `D j M Y` format (e.g. 1 Jan 2023)
- `delivery3_formatted` Next delivery date after delivery2 (index 3) in `D j M Y` format (e.g. 1 Jan 2023)
- `delivery4_formatted` Next delivery date after delivery3 (index 4) in `D j M Y` format (e.g. 1 Jan 2023)

Example response:

```JSON
{
  "postentoday": 0,
  "delivery0": "2023-05-15",
  "delivery1": "2023-05-16",
  "delivery2": "2023-05-19",
  "delivery3": "2023-05-22",
  "delivery4": "2023-05-23",
  "delivery0_formatted": "Mon 15 May 2023",
  "delivery1_formatted": "Tue 16 May 2023",
  "delivery2_formatted": "Fri 19 May 2023",
  "delivery3_formatted": "Mon 22 May 2023",
  "delivery4_formatted": "Tue 23 May 2023"
}
```
