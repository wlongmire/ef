<?php 
class wfMailer extends sfMailer
{
  /**
   * Creates a new message. Overridden to set $from to default mail user if null.
   *
   * @param string|array $from    The from address
   * @param string|array $to      The recipient(s)
   * @param string       $subject The subject
   * @param string       $body    The body
   *
   * @return Swift_Message A Swift_Message instance
   */
  public function compose($from = null, $to = null, $subject = null, $body = null)
  {
    if ($from == null)
    {
      $from = sfConfig::get('app_mail_default_user');
    }
    return parent::compose($from, $to, $subject, $body);
  }
  
  /**
   * Creates a new message with 2 bodies:
   * * 1 with $body and MIME type text/html.
   * * 1 with $body and tags stripped with MIME type text/plain. Stipped <br/>, </p>, and </div> tags and replaced with \n
   *
   * @param string|array $from    The from address
   * @param string|array $to      The recipient(s)
   * @param string       $subject The subject
   * @param string       $body    The body
   *
   * @return Swift_Message A Swift_Message instance
   */
  public function composeHtml($from = null, $to = null, $subject = null, $body = null)
  {
    if ($from == null)
    {
      $from = sfConfig::get('app_mail_default_user');
    }
    
    return Swift_Message::newInstance()
      ->setFrom($from)
      ->setTo($to)
      ->setSubject($subject)
      ->addPart($this->createPlainTextBody($body), 'text/plain')
      ->addPart($body, 'text/html');
  }

  /**
   * Sends a message using composeHtml.
   *
   * @param string|array $from    The from address
   * @param string|array $to      The recipient(s)
   * @param string       $subject The subject
   * @param string       $body    The body
   *
   * @return int The number of sent emails
   */
  public function composeAndSendHtml($from = null, $to = null, $subject = null, $body = null)
  {
    return $this->send($this->composeHtml($from, $to, $subject, $body));
  }

  /**
   * Attempts to create a plaintext message with all html tags stripped out and new lines inserted as necessary
   * @param $body
   * @return $body
   */
  public function createPlainTextBody($body)
  {
  	$body = preg_replace('/\<br\s*\/?\>/i', "\n", $body); //replace all <br/s> with new lines
  	$body = preg_replace('/\<\/p\s*\>/i', "</p>\n\n", $body); //append 2 newlines to the end of each </p>
    $body = preg_replace('/\<\/div\s*\>/i', "</div>\n\n", $body); //append 2 newlines to the end of each </div>
  	$body = strip_tags($body); //strip all tags from the body
  	return $body;
  }
}