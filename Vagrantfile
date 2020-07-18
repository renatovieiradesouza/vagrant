$script = <<-SCRIPT
cat /configs/id_bionic.pub >> .ssh/authorized_keys
SCRIPT

$scriptMysql = <<-SCRIPT
    apt-get update && \
    apt-get install -y mysql-server-5.7
    mysql -e "create user 'phpuser'@'%'identified by 'pass'; "
SCRIPT

$scriptChangeBindIpDefaultMysql = <<-SCRIPT
    cat /configs/mysqld.cnf > /etc/mysql/mysql.conf.d/mysqld.cnf
    service mysql restart
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"
  config.vm.network "forwarded_port", guest: 80, host: 8089
  config.vm.network "private_network", ip: "192.168.40.4"
  #config.vm.network "private_network", type: "dhcp"
  #config.vm.network "public_network", ip: "192.168.0.145"
  #config.vm.provision "shell", inline: "echo Hello, World >> hello.txt"
  #Você pode criar functions(provisioners) para chamar nos provisioners, veja
  config.vm.provision "shell", inline: $script
  config.vm.provision "shell", inline: $scriptMysql
  config.vm.provision "shell", inline: $scriptChangeBindIpDefaultMysql
  config.vm.synced_folder "./configs", "/configs"
  config.vm.synced_folder ".", "/vagrant", disabled: true

end

