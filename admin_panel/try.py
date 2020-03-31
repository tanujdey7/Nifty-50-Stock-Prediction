from pandas_datareader import DataReader
from datetime import datetime
from datetime import date
import sys

# ibm = DataReader(sys.argv[1] + ".NS",  'yahoo', datetime(2012, 1, 1), datetime.now())
# str = "../stock_data/" + sys.argv[1] + ".csv"
# ibm.to_csv(str)

if(sys.argv[1] == "^NSEI"):
    ibm = DataReader("^NSEI",  'yahoo', datetime(2012, 1, 1), datetime.now())
    str = "../stock_data/" + sys.argv[1] + ".csv"
    ibm.to_csv(str)

if(sys.argv[1] != "NSEI"):
    ibm = DataReader(sys.argv[1] + ".NS",  'yahoo', datetime(2012, 1, 1), datetime.now())
    str = "../stock_data/" + sys.argv[1] + ".csv"
    ibm.to_csv(str)