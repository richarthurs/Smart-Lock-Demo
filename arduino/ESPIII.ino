#include <SoftwareSerial.h>

#define DEBUG true

SoftwareSerial esp8266(2,3); // make RX Arduino line is pin 2, make TX Arduino line is pin 3.
// This means that you need to connect the TX line from the esp to the Arduino's pin 2

// and the RX line from the esp to the Arduino's pin 3

const int in1 = 10;
const int en = 11;
const int beeppin = 5;
const int analogInPin = A3;
const int buttonPin = 4;
int sensorValue = 0;
const int in2 = 12;// the number of the pushbutton pin
String pass = "ning0825";
String network = "Neil's iphone6";

void setup()
{
  Serial.begin(9600);
  esp8266.begin(9600); // your esp's baud rate might be different

  pinMode(11,OUTPUT);
  digitalWrite(11,LOW);

  pinMode(12,OUTPUT);
  digitalWrite(12,LOW);

  pinMode(13,OUTPUT);
  digitalWrite(13,LOW);

  pinMode(in1, OUTPUT);
  pinMode(in2, OUTPUT);
  pinMode(en, OUTPUT);  
  pinMode(beeppin, OUTPUT);
  pinMode(buttonPin, INPUT);

  sendData("AT+RST\r\n",2000,DEBUG); // reset module

  sendData("AT+CWMODE=1\r\n",1000,DEBUG); // configure as access point

  // sendData("AT+CWMODE=1\r\n",1000,DEBUG); // configure as access point

  String netinfo ="AT+CWJAP=\"";
  netinfo+=network;
  netinfo+= "\",\"";
  netinfo+= pass;
  netinfo+= "\"\r\n";
  sendData(netinfo,10000,DEBUG);

  //    Serial.println(netinfo);

  sendData("AT+CIFSR\r\n",1000,DEBUG); // get ip address
  sendData("AT+CIPMUX=1\r\n",1000,DEBUG); // configure for multiple connections
  sendData("AT+CIPSERVER=1,80\r\n",1000,DEBUG); // turn on server on port 80

  sendIP();

  beep();
  



}

void loop(){

// greater than or equal to, it's locked

int thresh = 300;
int previousEncoder = readEncoder();



  if(esp8266.available()) // check if the esp is sending a message 
  {


    if(esp8266.find("+IPD,"))
    {
      delay(1000); // wait for the serial buffer to fill up (read all the serial data)
      // get the connection id so that we can then disconnect
      int connectionId = esp8266.read()-48; // subtract 48 because the read() function returns 
      // the ASCII decimal value and 0 (the first decimal number) starts at 48

        esp8266.find("pin="); // advance cursor to "pin="

      int DBval = (esp8266.read()-48)*100; // get first number and multiply so it's in the 100's place
      DBval += (esp8266.read()-48)*10; // get second number and multiply so it's in the 10's place
      DBval += (esp8266.read()-48); // get one's digit
      DBval = DBval; // remove the +100 added by the site so it would always be three digits when sent

      int LocalVal = readEncoder();
      if(DBval <= LocalVal +50 || DBval >= LocalVal -50){  // checks to see if the DB val is close to the current value
        if(DBval < thresh){
          lock();
        } 
        if(DBval >= thresh){
          unlock(); 
        }
      }

      else{     // this handles cases where there's a discrepancy between the lock value and the DB. It just does what's right for the lock.
        if(locked(thresh)){
          unlock();
        }
        else{
          lock();
        }
      }
    
    //   else{    // this means the DB value and the current sensor value aren't very close
    //   if(DBval < thresh){
    //     lock();
    //   } 
    //   if(DBval >= thresh){
    //     unlock(); 
    //   }
    // }

      // make close command
      String closeCommand = "AT+CIPCLOSE="; 
      closeCommand+=connectionId; // append connection id
      closeCommand+="\r\n";

      sendData(closeCommand,1000,DEBUG); // close connection
    }
  }
  else{
    int buttonState = digitalRead(buttonPin);
    if(buttonState == HIGH){
      if(locked(thresh)){
        unlock();
      }
      else{
        lock();
      }
    }

   // Serial.println("read - prev: "+ readEncoder() - previousEncoder);
   //  Serial.println("prev - read: "+ previousEncoder-readEncoder());
   //  Serial.println("read: "+ readEncoder());
   //  Serial.println("prev: " +previousEncoder);



    // if(previousEncoder - readEncoder() <= 30 || readEncoder() - previousEncoder <= 30){    // handles the case where the lock was turned by hand
    //   beep();
    //   beep();

    //   if(locked(thresh)){
    //     unlock();
    //   }
    //   else{
    //     lock();
    //   }

    // }

 

  } // else


} // main

/*
* Name: sendData
 * Description: Function used to send data to ESP8266.
 * Params: command - the data/command to send; timeout - the time to wait for a response; debug - print to Serial window?(true = yes, false = no)
 * Returns: The response from the esp8266 (if there is a reponse)
 */
 String sendData(String command, const int timeout, boolean debug)
 {
  String response = "";

  esp8266.print(command); // send the read character to the esp8266

  long int time = millis();

  while( (time+timeout) > millis())
  {
    while(esp8266.available())
    {

      // The esp has data so display its output to the serial window 
      char c = esp8266.read(); // read the next character.
      response+=c;
    }  
  }

  if(debug)
  {
    Serial.print(response);
  }

  return response;
}

void lock(){
  digitalWrite(in1, HIGH);
  digitalWrite(in2, LOW);
  digitalWrite(en, HIGH);
  delay(1000);
  stop();
  int x = analogRead(analogInPin);
  sendDatatoServer(x);
  beep();
  
}
void stop(){
  digitalWrite(in1, LOW);
  digitalWrite(in2, LOW);
  digitalWrite(en, LOW);
}
bool locked(int thresh){
  int sensorValue = readEncoder();
  if(sensorValue >= thresh){
    return true; 
  }
  else return false;
}
int readEncoder(){
  int x = analogRead(analogInPin);
  return x + 100;
}

int sendDatatoServer(int sensorValue){  // used for sending the sensor value to the website
  sensorValue += 100;
  sendData("AT+CIPSTART=4,\"TCP\",\"richarthurs.com\",80\n",5000,DEBUG); // turn on server on port 80
  sendData("AT+CIPSEND=4,65\r\n",1000,DEBUG); // turn on server on port 80
  String getCommand = "GET /receiver.php?xval=";
  getCommand+=sensorValue;
  getCommand+=" HTTP/1.0\r\nHost:www.richarthurs.com\r\n\r\n";
  sendData(getCommand,5000,DEBUG); // turn on server on port 80
  return sensorValue;
}
int Complete(int completed){  // used for sending the sensor value to the website
  sendData("AT+CIPSTART=4,\"TCP\",\"richarthurs.com\",80\n",5000,DEBUG); // turn on server on port 80
  sendData("AT+CIPSEND=4,63\r\n",1000,DEBUG); // turn on server on port 80
  String getCommand = "GET /ajax.php?finished=";
  getCommand+=completed;
  getCommand+=" HTTP/1.0\r\nHost:www.richarthurs.com\r\n\r\n";
  sendData(getCommand,5000,DEBUG); // turn on server on port 80
  return completed;
}

void sendIP(){  // used for sending the sensor value to the website
  String IP = sendData("AT+CIFSR\r\n",1000,DEBUG); // gets the ip and puts it in string IP
  IP.remove(0, 8);
  IP.remove(10,IP.length());
  sendData("AT+CIPSTART=4,\"TCP\",\"richarthurs.com\",80\n",5000,DEBUG); // turn on server on port 80
  sendData("AT+CIPSEND=4,64\r\n",1000,DEBUG); // turn on server on port 80
  String getCommand = "GET /ip.php?ipval=";
  getCommand+=IP;
  getCommand+=" HTTP/1.0\r\nHost:www.richarthurs.com\r\n\r\n";
  sendData(getCommand,5000,DEBUG); // turn on server on port 80
}

void unlock(){
  digitalWrite(in1, LOW);
  digitalWrite(in2, HIGH);
  digitalWrite(en, HIGH);
  delay(1000);
  stop();
  int x = analogRead(analogInPin);
  sendDatatoServer(x);
  beep();
}

void beep(){
  digitalWrite(beeppin, HIGH);
  delay(200);
  digitalWrite(beeppin,LOW);
}




