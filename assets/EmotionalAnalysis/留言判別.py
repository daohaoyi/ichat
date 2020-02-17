import pickle
import numpy as np
from keras.models import load_model
from keras.preprocessing.sequence import pad_sequences
import mysql.connector
import jieba
import csv
import json
import time
from collections import Counter
#所需檔案 H5檔 userdict.txt synonyms.csv word_dict_TW.pk label_dict_TW.pk

def appraising(choose, n_id, sent, f_count,sent_jieba_array):
    appraise = 2
    try:
        input_shape = 180
        if '\n' in sent:
            sent = sent.replace("\n","")
        x = [[word_dictionary[word] for word in sent]]
        x = pad_sequences(maxlen=input_shape, sequences=x,
                            padding='post', value=0)
        
        y_predict = lstm_model.predict(x)
        #print(y_predict)
        label_dict = {v: k for k, v in output_dictionary.items()}
        #print(label_dict)
        #print('輸入語句: %s' % sent)
        #print('情感預測結果: %s\n' % label_dict[np.argmax(y_predict)])
        if label_dict[np.argmax(y_predict)] == '正面':
            appraise = 1
        elif label_dict[np.argmax(y_predict)] == '負面':
            appraise = 2
        
        if 'pr7Z0PCc886z_色情' in sent_jieba_array:
            #INSERT INTO `manager_message` (`chmeId`, `reason`, `verify`) VALUES ('2', '2', '1');
            mycursor.execute("INSERT INTO `manager_message` (`chmeId`, `reason`, `verify`) VALUES ('%s', '1', '0')" % (n_id))
        if '426Q_XiK9eJ6_暴力' in sent_jieba_array:
            mycursor.execute("INSERT INTO `manager_message` (`chmeId`, `reason`, `verify`) VALUES ('%s', '2', '0')" % (n_id))

        if choose == 'c':
            mycursor.execute("UPDATE chats_message SET c_appraise  = %s WHERE  chmeId = %d" % (appraise,n_id))
        elif choose == 'f':
            mycursor.execute("UPDATE friends_message SET f_appraise  = %s WHERE  frmeId = %d" % (appraise,n_id))
        return f_count
    except KeyError as err:
        #print("您輸入的句子有漢字不在詞彙表中，請重新輸入！")
        #print("不在詞彙表中的單詞為：%s." % err)
        if err.args[0] != '\r':
            why_is_doesnt_work.append(err.args[0])
        f_count += 1
        mydb.commit()
        return f_count

def execute(choose):
    sql = ''
    if choose == 'c':
        sql = "SELECT * FROM chats_message WHERE c_appraise IS NULL"
        print('正在執行公共聊天室部分...\n')
    elif choose == 'f':
        sql = "SELECT * FROM friends_message WHERE f_appraise IS NULL"
        print('正在執行好友聊天部分...\n')
    mycursor.execute(sql)
    myresult = mycursor.fetchall() 

    s_count = len(myresult)
    a_count = 0
    f_count = 0
    check = 0

    for x in myresult:
        n_id = x[0]
        sent = str(x[3])
        sent_jieba_array = []
        seg_list = jieba.cut_for_search(sent)
        for i in seg_list:
            sent_jieba_array.append(i)
        for i in range(0,len(sent_jieba_array)) :
            for j in synonyms:
                if sent_jieba_array[i] in j:
                    sent_jieba_array[i] = j[0]
        if 'Y18z6426g47t_疑問句' in sent_jieba_array:
            if choose == 'c':
                mycursor.execute("UPDATE chats_message SET c_appraise  = %s WHERE  chmeId = %d" % (3,n_id))
            elif choose == 'f':
                mycursor.execute("UPDATE friends_message SET f_appraise  = %s WHERE  frmeId = %d" % (3,n_id))
        else:
            f_count = appraising(choose, n_id, sent, f_count,sent_jieba_array)
        check,a_count,f_count = progress(a_count,check,s_count,f_count)
    mydb.commit()
    print('結束!還有%d個空白請加油' % (f_count))

def progress(a_count,check,s_count,f_count):
    a_count += 1
    if (((a_count%100) == 0) & (a_count != check)):
            print('目前已運算%d個總共%d個(已略過項目：%d個)' % (a_count-f_count,s_count-f_count,f_count))
            check = a_count
            progress_check = 0
            temp = ''
            for i in range(int(100*(float((a_count-f_count)/(s_count-f_count))))):
                progress_check += 1
                temp += '#'
            for i in range(100-progress_check):
                temp += '-' 
            temp =  ('[%s]' % (temp))
            print(temp + '  {:.2f}% \n'.format((a_count-f_count)/(s_count-f_count)*100))
    return check,a_count,f_count
while 1:
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        passwd="",
        database="ichat"
    )
    mycursor = mydb.cursor()

    model_save_path = 'C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/corpus_model_TW_2k.h5'
    lstm_model = load_model(model_save_path)


    jieba.load_userdict('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/userdict.txt')
    synonyms=[]
        # ↓記得改檔案路徑
    why_is_doesnt_work = []
    with open('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/synonyms.csv', newline='', encoding="utf-8-sig") as csvfile:
        rows = csv.reader(csvfile)
        for row in rows:
            synonyms.append(row)
    with open('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/word_dict_TW.pk', 'rb') as f:
            word_dictionary = pickle.load(f)
    with open('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/label_dict_TW.pk', 'rb') as f:
            output_dictionary = pickle.load(f)
    execute('c')
    execute('f')
    c = Counter(why_is_doesnt_work)

    with open('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/word_dict_TW.pk', 'rb') as f:
            word_dictionary = pickle.load(f)
    jsoninfo = json.dumps(word_dictionary)
    count = len(word_dictionary)
    temp = ''
    text = []
    print(c)
    for word, cnt in c.most_common(len(c)):  # 所有出現之字詞
        text.append(word)
    for i in text :
            if i not in word_dictionary:
                    temp += ', "%s":%d' % (i,count + 1)
    jsoninfo = (jsoninfo[:len(jsoninfo)-1] + temp + '}')
    dictinfo = json.loads(jsoninfo)
    file = open('C:/xampp/htdocs/ichat/assets/EmotionalAnalysis/word_dict_TW.pk', 'wb')
    data_one = pickle.dump(dictinfo,file)
    file.close()
    print('休息3秒')
    time.sleep(3)