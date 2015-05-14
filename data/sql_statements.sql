SELECT user.id, user.username, checkin_dairy.checkin_date, checkin_dairy.start_time, checkin_dairy.end_time,
SESSION , holiday_type
FROM user
INNER JOIN checkin_dairy ON checkin_dairy.user_id = user.id

SELECT user.id, user.username, checkin_dairy.checkin_date, (checkin_dairy.end_time - checkin_dairy.start_time) as total_time ,
SESSION , holiday_type
FROM user
INNER JOIN checkin_dairy ON checkin_dairy.user_id = user.id

SELECT user.id, user.username, checkin_dairy.checkin_date, 
sum(checkin_dairy.end_time - checkin_dairy.start_time) AS sum_time,
SESSION , holiday_type
FROM user
INNER JOIN checkin_dairy ON checkin_dairy.user_id = user.id
WHERE user_id =5 and substr(checkin_date, 4, 2) = 3
GROUP BY checkin_dairy.checkin_date