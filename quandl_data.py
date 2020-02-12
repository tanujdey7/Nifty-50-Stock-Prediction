import quandl

data = quandl.get("BSE/BOM539397", authtoken="z69con9zpBEQErLKHzBz")

print(data.tail())