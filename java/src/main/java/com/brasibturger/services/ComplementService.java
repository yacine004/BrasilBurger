package com.brasibturger.services;

import com.brasibturger.dtos.ComplementDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Complement;
import com.brasibturger.repositories.ComplementRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class ComplementService {
    
    private final ComplementRepository complementRepository;
    
    public List<ComplementDTO> getAllComplements() {
        log.info("Retrieving all complements");
        return complementRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<ComplementDTO> getActiveComplements() {
        log.info("Retrieving active complements");
        return complementRepository.findByStatut(Complement.Statut.ACTIF.toString()).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<ComplementDTO> getComplementsByType(String typeComplement) {
        log.info("Retrieving complements by type: {}", typeComplement);
        return complementRepository.findByTypeComplement(typeComplement).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public ComplementDTO getComplementById(Long id) {
        log.info("Retrieving complement with id: {}", id);
        Complement complement = complementRepository.findById(id)
                .orElseThrow(() -> new ApiException("Complement not found with id: " + id, 404));
        return convertToDTO(complement);
    }
    
    public ComplementDTO createComplement(ComplementDTO complementDTO) {
        log.info("Creating new complement: {}", complementDTO.getNom());
        Complement complement = convertToEntity(complementDTO);
        complement.setStatut(Complement.Statut.ACTIF);
        Complement saved = complementRepository.save(complement);
        return convertToDTO(saved);
    }
    
    public ComplementDTO updateComplement(Long id, ComplementDTO complementDTO) {
        log.info("Updating complement with id: {}", id);
        Complement complement = complementRepository.findById(id)
                .orElseThrow(() -> new ApiException("Complement not found with id: " + id, 404));
        
        complement.setNom(complementDTO.getNom());
        complement.setDescription(complementDTO.getDescription());
        complement.setPrix(complementDTO.getPrix());
        complement.setImage(complementDTO.getImage());
        complement.setTypeComplement(Complement.TypeComplement.valueOf(complementDTO.getTypeComplement()));
        complement.setStatut(Complement.Statut.valueOf(complementDTO.getStatut()));
        
        Complement updated = complementRepository.save(complement);
        return convertToDTO(updated);
    }
    
    public void deleteComplement(Long id) {
        log.info("Deleting complement with id: {}", id);
        if (!complementRepository.existsById(id)) {
            throw new ApiException("Complement not found with id: " + id, 404);
        }
        complementRepository.deleteById(id);
    }
    
    private ComplementDTO convertToDTO(Complement complement) {
        return ComplementDTO.builder()
                .nom(complement.getNom())
                .description(complement.getDescription())
                .prix(complement.getPrix())
                .image(complement.getImage())
                .typeComplement(complement.getTypeComplement() != null ? complement.getTypeComplement().toString() : null)
                .statut(complement.getStatut() != null ? complement.getStatut().toString() : null)
                .build();
    }
    
    private Complement convertToEntity(ComplementDTO complementDTO) {
        return Complement.builder()
                .nom(complementDTO.getNom())
                .description(complementDTO.getDescription())
                .prix(complementDTO.getPrix())
                .image(complementDTO.getImage())
                .typeComplement(Complement.TypeComplement.valueOf(complementDTO.getTypeComplement()))
                .statut(Complement.Statut.valueOf(complementDTO.getStatut()))
                .build();
    }
}
