# this Ruby program mimics this command line in code:
# gollum --ref ${branch} --live-preview ${PWD} > /dev/null 2>&1 &
# the reason being to give better control over the startup sequence
# for gollum, rather than relying on polling the process list in bash

# code based on
# http://stackoverflow.com/questions/15617347/fork-webrick-and-wait-for-start
# to ensure the server is actually started before we write its
# pid to the file /tmp/xwalk-website-gollum.pid

require 'rubygems'
require 'tmpdir'

# check whether gollum pid file exists and exit if it does
pid_file = File.join(Dir.tmpdir, 'xwalk-website-gollum.pid')

if File.exists?(pid_file)
  raise 'ERROR: pid_file ' + pid_file + ' already exists'
  Process.exit(1)
end

# clear the log file
log_file = File.join(Dir.tmpdir, 'xwalk-website-gollum.log')

if File.exists?(log_file)
  File.truncate(log_file, 0)
end

require 'rack'
require 'thin'
require 'gollum'
require 'gollum/app'

gollum_path = '.'

ref = ARGV.first || 'master'

wiki_options = {
  :live_preview  => true,
  :allow_uploads => false,
  :ref => ref
}

server_options = {
  :Port => 4567,
  :Bind => '0.0.0.0'
}

Precious::App.set(:gollum_path, gollum_path)
Precious::App.set(:wiki_options, wiki_options)

server = Thin::Server.new(server_options[:Bind], server_options[:Port]) do
  run Precious::App
end

server.pid_file = pid_file
server.log_file = log_file

server.daemonize()

server.start()
