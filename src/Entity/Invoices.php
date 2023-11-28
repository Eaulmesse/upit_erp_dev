<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoicesRepository::class)]
class Invoices
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $order_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sent_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $paid_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\Column(nullable: true)]
    private ?float $deposit_percent = null;

    #[ORM\Column(nullable: true)]
    private ?float $deposit_flat = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_update_date = null;

    #[ORM\Column]
    private ?float $tax_amount = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?float $discounts_amount = null;

    #[ORM\Column]
    private ?float $discounts_amount_with_tax = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $discounts_comments = null;

    #[ORM\Column]
    private ?float $taxes_rate = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[ORM\Column]
    private ?float $margin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mandatory_mentions = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $payment_mentions = null;

    #[ORM\Column]
    private ?int $theme_id = null;

    #[ORM\Column]
    private ?float $outstanding_amount = null;

    #[ORM\Column(nullable: true)]
    private ?int $frequency_in_months = null;

    #[ORM\Column(length: 255)]
    private ?string $business_user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $public_path = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $paid_invoice_pdf = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customer_portal_url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billing_address_street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billing_address_city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delivery_address_street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $delivery_address_city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parent_project = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $son_projects = null;


    #[ORM\Column(nullable: true)]
    private ?float $pre_tax_amount = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?Contracts $contracts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->order_number;
    }

    public function setOrderNumber(?string $order_number): static
    {
        $this->order_number = $order_number;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sent_date;
    }

    public function setSentDate(\DateTimeInterface $sent_date): static
    {
        $this->sent_date = $sent_date;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getPaidDate(): ?\DateTimeInterface
    {
        return $this->paid_date;
    }

    public function setPaidDate(?\DateTimeInterface $paid_date): static
    {
        $this->paid_date = $paid_date;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(?\DateTimeInterface $last_update_date): static
    {
        $this->last_update_date = $last_update_date;

        return $this;
    }

    public function getPreTaxAmount(): ?float
    {
        return $this->pre_tax_amount;
    }

    public function setPreTaxAmount(?float $pre_tax_amount): static
    {
        $this->pre_tax_amount = $pre_tax_amount;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(float $tax_amount): static
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getDepositPercent(): ?float
    {
        return $this->deposit_percent;
    }

    public function setDepositPercent(?float $deposit_percent): static
    {
        $this->deposit_percent = $deposit_percent;

        return $this;
    }

    public function getDepositFlat(): ?float
    {
        return $this->deposit_flat;
    }

    public function setDepositFlat(?float $deposit_flat): static
    {
        $this->deposit_flat = $deposit_flat;

        return $this;
    }

    public function getDiscountsAmount(): ?float
    {
        return $this->discounts_amount;
    }

    public function setDiscountsAmount(float $discounts_amount): static
    {
        $this->discounts_amount = $discounts_amount;

        return $this;
    }

    public function getDiscountsAmountWithTax(): ?float
    {
        return $this->discounts_amount_with_tax;
    }

    public function setDiscountsAmountWithTax(float $discounts_amount_with_tax): static
    {
        $this->discounts_amount_with_tax = $discounts_amount_with_tax;

        return $this;
    }

    public function getDiscountsComments(): ?string
    {
        return $this->discounts_comments;
    }

    public function setDiscountsComments(string $discounts_comments): static
    {
        $this->discounts_comments = $discounts_comments;

        return $this;
    }

    public function getTaxesRate(): ?float
    {
        return $this->taxes_rate;
    }

    public function setTaxesRate(float $taxes_rate): static
    {
        $this->taxes_rate = $taxes_rate;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMargin(): ?float
    {
        return $this->margin;
    }

    public function setMargin(float $margin): static
    {
        $this->margin = $margin;

        return $this;
    }

    public function getMandatoryMentions(): ?string
    {
        return $this->mandatory_mentions;
    }

    public function setMandatoryMentions(?string $mandatory_mentions): static
    {
        $this->mandatory_mentions = $mandatory_mentions;

        return $this;
    }

    public function getPaymentMentions(): ?string
    {
        return $this->payment_mentions;
    }

    public function setPaymentMentions(string $payment_mentions): static
    {
        $this->payment_mentions = $payment_mentions;

        return $this;
    }

    public function getThemeId(): ?int
    {
        return $this->theme_id;
    }

    public function setThemeId(int $theme_id): static
    {
        $this->theme_id = $theme_id;

        return $this;
    }

    public function getOutstandingAmount(): ?float
    {
        return $this->outstanding_amount;
    }

    public function setOutstandingAmount(float $outstanding_amount): static
    {
        $this->outstanding_amount = $outstanding_amount;

        return $this;
    }

    public function getFrequencyInMonths(): ?int
    {
        return $this->frequency_in_months;
    }

    public function setFrequencyInMonths(?int $frequency_in_months): static
    {
        $this->frequency_in_months = $frequency_in_months;

        return $this;
    }

    public function getBusinessUser(): ?string
    {
        return $this->business_user;
    }

    public function setBusinessUser(string $business_user): static
    {
        $this->business_user = $business_user;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return $this->public_path;
    }

    public function setPublicPath(string $public_path): static
    {
        $this->public_path = $public_path;

        return $this;
    }

    public function getPaidInvoicePdf(): ?string
    {
        return $this->paid_invoice_pdf;
    }

    public function setPaidInvoicePdf(?string $paid_invoice_pdf): static
    {
        $this->paid_invoice_pdf = $paid_invoice_pdf;

        return $this;
    }

    public function getCustomerPortalUrl(): ?string
    {
        return $this->customer_portal_url;
    }

    public function setCustomerPortalUrl(?string $customer_portal_url): static
    {
        $this->customer_portal_url = $customer_portal_url;

        return $this;
    }

    public function getBillingAddressStreet(): ?string
    {
        return $this->billing_address_street;
    }

    public function setBillingAddressStreet(?string $billing_address_street): static
    {
        $this->billing_address_street = $billing_address_street;

        return $this;
    }

    public function getBillingAddressCity(): ?string
    {
        return $this->billing_address_city;
    }

    public function setBillingAddressCity(?string $billing_address_city): static
    {
        $this->billing_address_city = $billing_address_city;

        return $this;
    }

    public function getDeliveryAddressStreet(): ?string
    {
        return $this->delivery_address_street;
    }

    public function setDeliveryAddressStreet(?string $delivery_address_street): static
    {
        $this->delivery_address_street = $delivery_address_street;

        return $this;
    }

    public function getDeliveryAddressCity(): ?string
    {
        return $this->delivery_address_city;
    }

    public function setDeliveryAddressCity(?string $delivery_address_city): static
    {
        $this->delivery_address_city = $delivery_address_city;

        return $this;
    }

    public function getContracts(): ?Contracts
    {
        return $this->contracts;
    }

    public function setContracts(?Contracts $contracts): static
    {
        $this->contracts = $contracts;

        return $this;
    }
    

}
