<?php
/**
 * Build on top of the official mandrill API PHP wrapper
 *
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       01.10.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Class CmandrillInbound
 * 
 * @since  3.0
 */
class CmandrillInbound
{
	/**
	 * The constructor
	 *
	 * @param   CmandrillQuery  $master  - the mandrill class
	 */
	public function __construct(CmandrillQuery $master)
	{
		$this->master = $master;
	}

	/**
	 * List the domains that have been configured for inbound delivery
	 * 
	 * @return array the inbound domains associated with the account
	 *     - return[] object the individual domain info
	 *         - domain string the domain name that is accepting mail
	 *         - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
	 *         - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
	 */
	public function domains()
	{
		$params = array();

		return $this->master->call('inbound/domains', $params);
	}

	/**
	 * List the mailbox routes defined for an inbound domain
	 *
	 * @param   string  $domain  - the domain to check
	 *
	 * @return array the routes associated with the domain
	 *     - return[] object the individual mailbox route
	 *         - pattern string the search pattern that the mailbox name should match
	 *         - url string the webhook URL where inbound messages will be published
	 */
	public function routes($domain)
	{
		$params = array("domain" => $domain);

		return $this->master->call('inbound/routes', $params);
	}

	/**
	 * Take a raw MIME document destined for a domain with inbound domains set up, and send it to the inbound hook exactly as
	 * if it had been sent over SMTP
	 *
	 * @param   string      $raw_message     - the full MIME document of an email message
	 * @param   array|null  $to              - optionally define the recipients to receive the message - otherwise we'll use the To, Cc, and Bcc headers
	 *                                         provided in the document
	 *                                         to[] string the email address of the recipient
	 * @param   string      $mail_from       - the address specified in the MAIL FROM stage of the SMTP conversation. Required for the SPF check.
	 * @param   string      $helo            - the identification provided by the client mta in the MTA state of the SMTP conversation.
	 *                                         Required for the SPF check.
	 * @param   string      $client_address  - the remote MTA's ip address. Optional; required for the SPF check.
	 *
	 * @return array an array of the information for each recipient in the message (usually one) that matched an inbound route
	 *     - return[] object the individual recipient information
	 *         - email string the email address of the matching recipient
	 *         - pattern string the mailbox route pattern that the recipient matched
	 *         - url string the webhook URL that the message was posted to
	 */
	public function sendRaw($raw_message, $to = null, $mail_from = null, $helo = null, $client_address = null)
	{
		$params = array("raw_message" => $raw_message, "to" => $to, "mail_from" => $mail_from, "helo" => $helo, "client_address" => $client_address);

		return $this->master->call('inbound/send-raw', $params);
	}
}
