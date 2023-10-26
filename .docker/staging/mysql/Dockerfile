FROM  mysql:8.1.0

COPY my.cnf /etc/mysql/my.cnf
RUN chmod 600 /etc/mysql/my.cnf

RUN chown mysql:mysql -R /etc/mysql
RUN mkdir -p /var/lib/mysql

RUN chown mysql:mysql -R /var/lib/mysql
USER mysql

EXPOSE 3306