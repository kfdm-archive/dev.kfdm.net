#!/usr/bin/env ruby

require 'rubygems'
require 'json'

search = ARGV[1] || 'moment'

json = `curl -s -d "search=#{search}" -H "X-Requested-With:XMLHttpRequest" http://tsun.co/quotes/search`

obj = JSON.parse(json)

obj['quotes'].each do |quote|
	puts "##{quote['id']} #{quote['quote']}\n\n"
end