from pandas_datareader import DataReader
from datetime import datetime
from datetime import date
import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  passwd="",
  database="predictor"
)

mycursor = mydb.cursor()

mycursor.execute("SELECT Symbol FROM s_c_details")

myresult = mycursor.fetchall()
s = [None] * 51
a = [None] * 51
i = 0
for x in myresult:
  s[i]  = ''.join(x) + ".NS"
  print(s[i])
  a[i] = ''.join(x)
  i+=1
  if(i==49):
    break

for i in range(0,50):
    ibm = DataReader(s[i],  'yahoo', datetime(2012, 1, 1), datetime.now())
    str = "stock_data/" + a[i] + ".csv"
    ibm.to_csv(str)

