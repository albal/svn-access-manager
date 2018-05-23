FROM centos:7

MAINTAINER Thomas Krieger <tom@svn-access-manager.org>
LABEL Description="Docker container for SVN Access Manager on PHP7" Version="0.5.7.0"

ENV CHARSET=latin1
ENV COLLATION=latin1_swedish_ci
ENV DBHOST=localhost
ENV DBNAME=svnam
ENV DBUSER=svnam
ENV DBPASS=svnampass
ENV SVNAMVERSION=branches/svnam-0.5-install-fix

ENV MYSQL_ROOT_PASSWORD=rootpw
ENV MYSQL_DATABASE=svnam
ENV MYSQL_USER=svnam
ENV MYSQL_PASSWORD=svnampass

RUN yum clean all && \
    yum -y update && \
    yum -y install hostname

RUN curl -l -k -s http://repo.webtatic.com/yum/el7/webtatic-release.rpm -o /tmp/webtatic-release.rpm && \
    curl -l -k -s https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -o /tmp/epel-release-latest-7.noarch.rpm && \
    yum -y localinstall /tmp/webtatic-release.rpm /tmp/epel-release-latest-7.noarch.rpm && \
    rpm --import /etc/pki/rpm-gpg/RPM*

RUN yum -y install mysql unzip tar curl subversion httpd mod_dav_svn \
                   viewvc php70w php70w-cli php70w-mbstring systemd \
                   php70w-bcmath php70w-mcrypt php70w-mysql python34-devel \
                   mariadb-server bind-utils pwgen psmisc vim-enhanced && \
    yum clean all && \
    rm -rf /var/cache/yum && \
    mkdir -p /usr/share && \
    curl -l -k -s https://bootstrap.pypa.io/get-pip.py | python3.4 && \
    pip3 install chaperone

COPY files/svn-access-manager.conf /etc/httpd/conf.d/svn-access-manager.conf
COPY files/php.ini /etc/
COPY files/httpd.conf /etc/httpd/conf/httpd.conf

RUN cd /usr/share && \
    /bin/svn checkout https://svn.code.sf.net/p/svn-access-mana/code/${SVNAMVERSION} svn-access-manager && \
    chown -R 27:27 svn-access-manager && \
    mkdir /etc/svn && \
    mkdir /etc/svn-access-manager && \
    mkdir -p /svn/repos && \
    touch /etc/svn/viewvc-apache.conf && \
    chown apache:apache /etc/svn && \
    chown apache:apache /etc/svn-access-manager && \
    cd /svn/repos && \
    svnadmin create --fs-type fsfs test1 && \
    svnadmin create --fs-type fsfs test2 && \
    chown -R apache:apache /svn/repos && \
    rm -f /tmp/svn-access-manager.tar.gz /tmp/webtatic-release.rpm

COPY files/config.inc.php /etc/svn-access-manager/config.inc.php
COPY files/svnam-dump.sql /tmp/db.dump
COPY files/chaperone.conf /etc/chaperone.d/chaperone.conf
COPY files/user_service.sh /user_service.sh
COPY files/mysql-setup.sh /mysql-setup.sh
COPY files/setup.sh /setup.sh
COPY files/mysql-load.sh /mysql-load.sh

RUN chmod 755 /user_service.sh /setup.sh /mysql-load.sh /mysql-setup.sh
RUN chown apache:apache /etc/svn-access-manager/config.inc.php /tmp/db.dump
RUN chown mysql:mysql /tmp/db.dump

COPY files/fix-permissions.sh ./
RUN chmod 755 ./fix-permissions.sh ; \
    ./fix-permissions.sh /var/lib/mysql/   && \
    ./fix-permissions.sh /var/log/mariadb/ && \
    ./fix-permissions.sh /var/run/
     
COPY files/docker-entrypoint.sh /
RUN chmod 755 /docker-entrypoint.sh

EXPOSE 80

VOLUME [ "/var/log/httpd", "/etc/svn-access-manager", "/svn/repos", "/var/lib/mysql", "/var/log/mariadb" ]

ENTRYPOINT ["/usr/bin/chaperone"]
