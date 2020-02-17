import requests
import jieba
import mysql.connector
from bs4 import BeautifulSoup
from collections import Counter
import csv
import json
import time

mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    passwd="",
    database="ichat"
)
while(True):
    # ↓記得改檔案路徑
    jieba.load_userdict('C:/xampp/htdocs/ichat/assets/keyword/userdict.txt')

    mycursor = mydb.cursor()
    mycursor.execute("SELECT chatName FROM chats")
    myresult = mycursor.fetchall()     # fetchall() 获取所有记录

    Not_included = ['time','Greeting']
    # ↓記得改檔案路徑
    with open('C:/xampp/htdocs/ichat/assets/keyword/keyword.csv', newline='', encoding="utf-8") as csvfile:

        # 讀取 CSV 檔案內容
        rows = csv.reader(csvfile)
        # 以迴圈輸出每一列
        for row in rows:
            Not_included.append(row[0])
    word = []
    text = ""

    for x in myresult:
        text += str(x[0])

    mycursor.execute("SELECT chatMessage FROM chats_message")
    myresult = mycursor.fetchall()     # fetchall() 获取所有记录
    for x in myresult:
        text += str(x[0])

    seg_list = jieba.cut_for_search(text)
    for i in seg_list:
        word.append(i)  # 增加詞

    count = 0 #前10名計算起始點
    

    synonyms=[]
    # ↓記得改檔案路徑
    with open('C:/xampp/htdocs/ichat/assets/keyword/synonyms.csv', newline='', encoding="utf-8-sig") as csvfile:

        # 讀取 CSV 檔案內容
        rows = csv.reader(csvfile)
        # 以迴圈輸出每一列
        for row in rows:
            synonyms.append(row)
    # print(synonyms)
    for i in range(0,len(word)) :
        for j in synonyms:
            if word[i] in j:
                word[i] = j[0]

    c = Counter(word)
    # ↓前十名 記得改檔案路徑
    with open('C:/xampp/htdocs/ichat/assets/keyword/output.csv', 'w', newline='', encoding="utf-8") as csvfile:
        writer = csv.writer(csvfile)
        for word, cnt in c.most_common(len(c)):  # 所有出現之字詞
            if (len(word) >= 2) & (cnt >= 3) & (word not in Not_included):  # 字串長度超過兩個字的字詞、出現3次以上(含)、不在略過字詞中
                print(word, cnt)
                count += 1
                writer.writerow([word, cnt])
            if(count == 10):
                break
    
    # ↓全部 記得改檔案路徑
    # with open('G:/Desktop/output_reference.csv', 'w', newline='', encoding="utf-8") as csvfile:
    #     writer = csv.writer(csvfile)
    #     for word, cnt in c.most_common(len(c)):  # 所有出現之字詞
    #         if (len(word) >= 2) & (cnt >= 3) & (word not in Not_included) & (word != 'time'):  # 字串長度超過兩個字的字詞、出現3次以上(含)、不在略過字詞中
    #             print(word, cnt)
    #             count += 1
    #             writer.writerow([word, cnt])

    print("\n休息1分\n")
    time.sleep(60)