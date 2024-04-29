#include <WiFi.h>
#include <HTTPClient.h>

#define TRIGGER_PIN_RICE 12 // Ultrasonic sensor trigger pin for Rice Stock
#define ECHO_PIN_RICE 14    // Ultrasonic sensor echo pin for Rice Stock

#define TRIGGER_PIN_PULSE 25 // Ultrasonic sensor trigger pin for Pulse Stock
#define ECHO_PIN_PULSE 26    // Ultrasonic sensor echo pin for Pulse Stock

const char* ssid = "UK Galaxy F23 5G";
const char* password = "Usha8899";
const char* serverAddress = "http://192.168.32.103/smart_kitchen_container_project"; // Change this to your server address

double duration;
double distanceRice, distancePulse;

void setup() {
  Serial.begin(115200);
  pinMode(TRIGGER_PIN_RICE, OUTPUT);
  pinMode(ECHO_PIN_RICE, INPUT);
  pinMode(TRIGGER_PIN_PULSE, OUTPUT);
  pinMode(ECHO_PIN_PULSE, INPUT);
  connectToWiFi();
}

void loop() {
  // Read distances from ultrasonic sensors
  distanceRice = getDistance(TRIGGER_PIN_RICE, ECHO_PIN_RICE);
  distancePulse = getDistance(TRIGGER_PIN_PULSE, ECHO_PIN_PULSE);

  // Assuming you have some mechanism to obtain or generate the ID
  String deviceId = "esp32_01"; // Replace with the actual device ID
  
  // Send data to server
  postDataToServer(deviceId, distanceRice, distancePulse);

  // Fetch real-time data from server
  fetchDataFromServer();

  delay(5000); // Adjust delay as needed
}

double getDistance(int triggerPin, int echoPin) {
  digitalWrite(triggerPin, LOW);
  delayMicroseconds(2);
  digitalWrite(triggerPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(triggerPin, LOW);
  duration = pulseIn(echoPin, HIGH);
  double distance = duration*0.034/2;
  Serial.println(distance);
  double stock = ((13-(distance))/13)*100;
  return stock;
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void postDataToServer(String id, double riceStock, double pulseStock) {
  HTTPClient http;
  String url = serverAddress;
  url += "/updatedata_and_recordtable.php";
  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Construct POST data string
  String postData = "ID=" + id + "&Rice_Stock=" + String(riceStock)+ "&Pulse_Stock=" + String(pulseStock);
  
  int httpCode = http.POST(postData);
  String response = http.getString();

  Serial.print("HTTP response code: ");
  Serial.println(httpCode);
  Serial.print("Server response: ");
  Serial.println(response);

  http.end();
}

void fetchDataFromServer() {
  HTTPClient http;
  String url = serverAddress;
  url += "/getdata.php";
  http.begin(url);
  int httpCode = http.GET();
  String payload = http.getString();

  if (httpCode == HTTP_CODE_OK) {
    Serial.println("Data fetched successfully:");
    Serial.println(payload);
    // You can parse the JSON payload here and take necessary actions
  } else {
    Serial.print("Error fetching data. HTTP code: ");
    Serial.println(httpCode);
  }

  http.end();
}
