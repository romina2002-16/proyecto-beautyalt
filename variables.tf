variable "do_token" {
  description = "DigitalOcean Personal Access Token"
  type        = string
  sensitive   = true
}

variable "ssh_fingerprint" {
  description = "Fingerprint de tu clave pública SSH en DigitalOcean"
  type        = string
}
