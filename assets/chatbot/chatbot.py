import requests
import jieba
import csv
import json
import datetime
import twstock
import sys
#先把15~18行註解看完
#請到聊天機器人資料夾下載並修改更新再使用
#所需檔案:city_to_weather.csv ，synonyms.csv ，country_to_area.csv ，keyword.csv ，qu.txt
jieba.load_userdict('C:/xampp/htdocs/ichat/assets/chatbot/qu.txt')
question = ''
for i in range(1,len(sys.argv)):
    question += (sys.argv[i]+' ')
#整合.py除了import sys
#只有這邊有改
#sys.argv[0]是檔名
#sys.argv[0]之後是php傳送過來的資料
def error_display():
        print('Hello！\n我是iChat專屬聊天機器人！\n使用搜尋建議以斷句且越精確越好\n如：1.高雄 天氣\n2.英國 時間\n3.1234 股票\n\n如果沒有您要的解答\n請給予管理員意見謝謝！！')

bot_start = datetime.datetime.utcnow()

seg_list = jieba.cut_for_search(question)

Not_included = []
with open('C:/xampp/htdocs/ichat/assets/chatbot/keyword.csv', newline='', encoding="utf-8") as csvfile:
    rows = csv.reader(csvfile)
    for row in rows:
        Not_included.append(row[0])

important = []
for i in seg_list:
    if (i not in Not_included) & (len(i) > 1):
        important.append(i)
        # print(i)

synonyms = []

with open('C:/xampp/htdocs/ichat/assets/chatbot/synonyms.csv', newline='', encoding="utf-8-sig") as csvfile:
        rows = csv.reader(csvfile)
        for row in rows:
            synonyms.append(row)
for i in range(0,len(important)) :
    for j in synonyms:
        if important[i] in j:
            important[i] = j[0]

if "stock" in important :
    # print("這是股票問題")
    all_stock_dict = []
    stock_code = []
    stock_name = []
    stock_market = []
    stock_group = []
    check = 0
    all_stock_dict.append(twstock.codes)
    for i in all_stock_dict:
        for j in i:
            stock_code.append(j)
    for i in all_stock_dict:
        for j in stock_code:
            stock_name.append(i[j][2])
            stock_market.append(i[j][5])
            stock_group.append(i[j][6])
    for i in important:
        if i.isdigit() :
            if i in stock_code:
                check = 1
                qu = i
        if i.isalpha():
            if i in stock_name:
                check = 1
                qu = stock_code[stock_name.index(i)]
    if check == 1:
        stock_id = stock_code.index(qu)
        print('編號:%s 名稱:%s 狀態:%s 業別:%s' % (stock_code[stock_id],stock_name[stock_id],stock_market[stock_id],stock_group[stock_id]))
        print('搜尋價格較慢，請耐心等候謝謝！！')
        stock_real = twstock.realtime.get(qu)
        stock_history = twstock.Stock(qu)
        stock_price = stock_history.price[-7:]
        stock_high = stock_history.high[-7:]
        stock_low = stock_history.low[-7:]
        stock_date = stock_history.date[-7:]
        print('\n近七日價格')
        print('日期                     收盤價     最高      最低')
        for i in range(len(stock_price)):
            print('%s       %s      %s      %s' %(stock_date[i],stock_price[i],stock_high[i],stock_low[i]))
        print('\n全名:%s \n時間:%s \n最新價格:%s'%(stock_real['info']['fullname'],stock_real['info']['time'],stock_real['realtime']['latest_trade_price']))
        print('\n最佳價格及交易數')
        for i in range(len(stock_real['realtime']['best_bid_price'])):
            print('價格:%s 交易數:%s' % (stock_real['realtime']['best_bid_price'][i],stock_real['realtime']['best_bid_volume'][i]))
        bot_end = datetime.datetime.utcnow()
    else:
        error_display()

elif "weather" in important :
    # print("這是天氣問題")
    city = []
    city_id = 0
    city_name = ''
    #city_to_weather 各個格子功能 0.縣市編號 1.顯示縣市 2.顯示資料所取的站名 2以後都是用來比對地名用
    with open('C:/xampp/htdocs/ichat/assets/chatbot/city_to_weather.csv', newline='', encoding="utf-8-sig") as csvfile:
        rows = csv.reader(csvfile)
        for row in rows:
            city.append(row)
    for i in range(0,len(important)) :
        for j in city:
            if important[i] in j:
                city_id = j[0]
                city_name = j[1]
                StationName = j[2]
    if city_id == 0:
        print("請記得輸入地名喔~(以縣市為單位)，或是給予管理員意見謝謝！！")
        exit()
    web = "https://www.cwb.gov.tw/Data/js/Observe/County/"+city_id+".js"
    # print(web)
    r = requests.get(web)
    r.encoding = 'utf8'
    jss = r.text.replace('var ST = ','')
    jss = jss[:(jss.index(';'))]
    check = 0
    count = len(jss)
    while i < count:
        if (jss[i]== '"')& (check == 0):
            check = 1
        elif (jss[i] == "'") & (check == 1):
            jss = jss[:i]+jss[i+1:]
            count -= 1
        elif (jss[i] == '"') & (check == 1):
            check = 0
        i += 1
    jss = jss.replace("'",'"')
    
    # try :
    jss = json.loads(jss)
    for i in jss.values():
        for j in i.values():
            if StationName == j['StationName']['C']:
                print("地點:%s \n氣溫:%s°C\n濕度:%s\n累積雨量:%s毫米\n測量時間:%s %s" % (city_name,j['Temperature']['C']['C'],j['Humidity']['C']+'%',j['Rain']['C'],j['Date'],j['Time']))
                # print("日期:%s 時間:%s 站名:%s 溫度:%s°C 相對濕度:%s 累積雨量:%s毫米 風向:%s" % (j['Date'],j['Time'],j['StationName']['C'],j['Temperature']['C']['C'],j['Humidity']['C']+'%',j['Rain']['C'],j['WindDir']['C']))
    # except:
    #     print("很抱歉!儀器故障或者目前無資料")
    bot_end = datetime.datetime.utcnow()

elif "time" in important :
    # print("這是時間問題")
    country = []
    country_area = ''
    country_name = ''
    with open('C:/xampp/htdocs/ichat/assets/chatbot/country_to_area.csv', newline='', encoding="utf-8-sig") as csvfile:
        rows = csv.reader(csvfile)
        for row in rows:
            country.append(row)
    for i in range(0,len(important)) :
        for j in country:
            if important[i] in j:
                country_area = j[0]
                country_name = important[i]
    if country_area == '':
        error_display()
        exit()
    timenow = (datetime.datetime.utcnow()+datetime.timedelta(hours=((int)(country_area))))
    print("%s目前時間為:%s年%s月%s日%s時%s分(24hr)"%(country_name,timenow.year,timenow.month,timenow.day,timenow.hour,timenow.minute))
    bot_end = datetime.datetime.utcnow()

elif "Greeting" in important :
    error_display()

else :
    error_display()