<?php

namespace App\Entity;

use App\Repository\QuotationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotationsRepository::class)]
class Quotations
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $expiry_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $sent_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_update_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_customer_answer = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?Companies $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $global_discount_amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $global_discount_with_tax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $global_discount_unit_is_percent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $global_discount_comments = null;

    #[ORM\Column(nullable: true)]
    private ?float $pre_tax_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $tax_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_amount = null;

    #[ORM\Column(nullable: true)]
    private ?float $margin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payments_to_display_in_pdf = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $signature_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $signature_timezone_type = null;

    #[ORM\Column(nullable: true)]
    private ?string $signature_timezone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $public_path = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customer_portal_url = null;

    #[ORM\OneToMany(mappedBy: 'quotations_id', targetEntity: QuotationLines::class)]
    private Collection $quotation_lines;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?Projects $project = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?Opportunities $opportunitiy = null;

    #[ORM\ManyToOne(inversedBy: 'quotations')]
    private ?Contracts $contract = null;

    public function __construct()
    {
        $this->quotation_lines = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiry_date;
    }

    public function setExpiryDate(\DateTimeInterface $expiry_date): static
    {
        $this->expiry_date = $expiry_date;

        return $this;
    }

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sent_date;
    }

    public function setSentDate(?\DateTimeInterface $sent_date): static
    {
        $this->sent_date = $sent_date;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateCustomerAnswer(): ?\DateTimeInterface
    {
        return $this->date_customer_answer;
    }

    public function setDateCustomerAnswer(?\DateTimeInterface $date_customer_answer): static
    {
        $this->date_customer_answer = $date_customer_answer;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?Companies
    {
        return $this->company;
    }

    public function setCompany(?Companies $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getOpportunitiy(): ?Opportunities
    {
        return $this->opportunitiy;
    }

    public function setOpportunitiy(?Opportunities $opportunitiy): static
    {
        $this->opportunitiy = $opportunitiy;

        return $this;
    }

    public function getContract(): ?Contracts
    {
        return $this->contract;
    }

    public function setContract(?Contracts $contract): static
    {
        $this->contract = $contract;

        return $this;
    }

    public function getGlobalDiscountAmount(): ?string
    {
        return $this->global_discount_amount;
    }

    public function setGlobalDiscountAmount(?string $global_discount_amount): static
    {
        $this->global_discount_amount = $global_discount_amount;

        return $this;
    }

    public function getGlobalDiscountWithTax(): ?string
    {
        return $this->global_discount_with_tax;
    }

    public function setGlobalDiscountWithTax(?string $global_discount_with_tax): static
    {
        $this->global_discount_with_tax = $global_discount_with_tax;

        return $this;
    }

    public function getGlobalDiscountUnitIsPercent(): ?string
    {
        return $this->global_discount_unit_is_percent;
    }

    public function setGlobalDiscountUnitIsPercent(?string $global_discount_unit_is_percent): static
    {
        $this->global_discount_unit_is_percent = $global_discount_unit_is_percent;

        return $this;
    }

    public function getGlobalDiscountComments(): ?string
    {
        return $this->global_discount_comments;
    }

    public function setGlobalDiscountComments(?string $global_discount_comments): static
    {
        $this->global_discount_comments = $global_discount_comments;

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

    public function setTaxAmount(?float $tax_amount): static
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?float $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getMargin(): ?float
    {
        return $this->margin;
    }

    public function setMargin(?float $margin): static
    {
        $this->margin = $margin;

        return $this;
    }

    public function getPaymentsToDisplayInPdf(): ?string
    {
        return $this->payments_to_display_in_pdf;
    }

    public function setPaymentsToDisplayInPdf(?string $payments_to_display_in_pdf): static
    {
        $this->payments_to_display_in_pdf = $payments_to_display_in_pdf;

        return $this;
    }

    public function getSignatureDate(): ?\DateTimeInterface
    {
        return $this->signature_date;
    }

    public function setSignatureDate(?\DateTimeInterface $signature_date): static
    {
        $this->signature_date = $signature_date;

        return $this;
    }

    public function getSignatureTimezone(): ?string
    {
        return $this->signature_timezone;
    }

    public function setSignatureTimezone(?string $signature_timezone): static
    {
        $this->signature_timezone = $signature_timezone;

        return $this;
    }

    public function getSignatureTimezoneType(): ?int
    {
        return $this->signature_timezone_type;
    }

    public function setSignatureTimezoneType(?int $signature_timezone_type): static
    {
        $this->signature_timezone_type = $signature_timezone_type;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return $this->public_path;
    }

    public function setPublicPath(?string $public_path): static
    {
        $this->public_path = $public_path;

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

    /**
     * @return Collection<int, QuotationLines>
     */
    public function getQuotationLines(): Collection
    {
        return $this->quotation_lines;
    }

    public function addQuotationLine(QuotationLines $quotationLine): static
    {
        if (!$this->quotation_lines->contains($quotationLine)) {
            $this->quotation_lines->add($quotationLine);
            $quotationLine->setQuotationsId($this);
        }

        return $this;
    }

    public function removeQuotationLine(QuotationLines $quotationLine): static
    {
        if ($this->quotation_lines->removeElement($quotationLine)) {
            // set the owning side to null (unless already changed)
            if ($quotationLine->getQuotationsId() === $this) {
                $quotationLine->setQuotationsId(null);
            }
        }

        return $this;
    }

    

    
}
